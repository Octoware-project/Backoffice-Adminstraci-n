<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnidadHabitacional;
use App\Models\Persona;
use Illuminate\Support\Facades\Validator;

class UnidadHabitacionalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = UnidadHabitacional::with(['personas.user']);

            // Filtro por número de departamento
            if ($request->filled('numero_departamento')) {
                $query->where('numero_departamento', 'LIKE', "%{$request->numero_departamento}%");
            }

            // Filtro por piso
            if ($request->filled('piso')) {
                $query->where('piso', $request->piso);
            }

            // Filtro por estado
            if ($request->filled('estado')) {
                $query->where('estado', $request->estado);
            }

            // Filtro por ocupación
            if ($request->filled('filter_ocupacion')) {
                if ($request->filter_ocupacion === 'ocupada') {
                    $query->whereHas('personas');
                } elseif ($request->filter_ocupacion === 'disponible') {
                    $query->whereDoesntHave('personas');
                }
            }

            // Ordenamiento
            $sortField = $request->get('sort', 'numero_departamento');
            $sortDirection = 'asc'; // Por defecto ascendente
            
            $allowedSortFields = ['numero_departamento', 'piso', 'estado', 'created_at'];
            if (!in_array($sortField, $allowedSortFields)) {
                $sortField = 'numero_departamento';
            }

            $unidades = $query->orderBy($sortField, $sortDirection)->paginate(15);
            
            return view('admin.unidades.index', compact('unidades'));
        } catch (\Exception $e) {
            \Log::error('Error al listar unidades habitacionales: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las unidades habitacionales.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.unidades.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'numero_departamento' => 'required|string|max:50|unique:unidades_habitacionales,numero_departamento',
                'piso' => 'required|integer|min:1',
                'estado' => 'required|in:En construccion,Finalizado',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            UnidadHabitacional::create($request->all());

            return redirect()->route('unidades.index')
                ->with('success', 'Unidad habitacional creada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error al crear unidad habitacional: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la unidad habitacional.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $unidad = UnidadHabitacional::with(['personas.user', 'planesTrabajos.user'])
                ->findOrFail($id);

            return view('admin.unidades.show', compact('unidad'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar unidad habitacional: ' . $e->getMessage());
            return redirect()->route('unidades.index')->with('error', 'Unidad habitacional no encontrada.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $unidad = UnidadHabitacional::findOrFail($id);
            return view('admin.unidades.edit', compact('unidad'));
        } catch (\Exception $e) {
            \Log::error('Error al editar unidad habitacional: ' . $e->getMessage());
            return redirect()->route('unidades.index')->with('error', 'Unidad habitacional no encontrada.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $unidad = UnidadHabitacional::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'numero_departamento' => 'required|string|max:50|unique:unidades_habitacionales,numero_departamento,' . $id,
                'piso' => 'required|integer|min:1',
                'estado' => 'required|in:En construccion,Finalizado',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $unidad->update($request->all());

            return redirect()->route('unidades.show', $unidad->id)
                ->with('success', 'Unidad habitacional actualizada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error al actualizar unidad habitacional: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la unidad habitacional.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $unidad = UnidadHabitacional::findOrFail($id);

            // Verificar si tiene residentes asignados
            $cantidadPersonas = $unidad->personas()->count();
            if ($cantidadPersonas > 0) {
                return redirect()->back()
                    ->with('error', "No se puede eliminar la unidad Departamento {$unidad->numero_departamento} - Piso {$unidad->piso} porque tiene {$cantidadPersonas} persona" . ($cantidadPersonas > 1 ? 's' : '') . " asignada" . ($cantidadPersonas > 1 ? 's' : '') . ". Primero debe desasignar todas las personas.");
            }

            $numeroDepartamento = $unidad->numero_departamento;
            $piso = $unidad->piso;
            
            $unidad->delete();

            return redirect()->route('unidades.index')
                ->with('success', "Unidad habitacional Departamento {$numeroDepartamento} - Piso {$piso} eliminada exitosamente.");
        } catch (\Exception $e) {
            \Log::error('Error al eliminar unidad habitacional: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la unidad habitacional.');
        }
    }

    /**
     * Asignar una persona a una unidad habitacional
     */
    public function asignarPersona(Request $request, $unidadId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'persona_id' => 'required|exists:personas,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $unidad = UnidadHabitacional::findOrFail($unidadId);
            $persona = Persona::findOrFail($request->persona_id);

            // Verificar que la persona no tenga ya una unidad asignada
            if ($persona->unidad_habitacional_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La persona ya tiene una unidad asignada.'
                ], 422);
            }

            // Verificar que la persona esté en estado válido
            if (!in_array($persona->estadoRegistro, ['Aceptado', 'Inactivo'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'La persona debe estar en estado "Aceptado" o "Inactivo" para ser asignada.'
                ], 422);
            }

            $persona->update([
                'unidad_habitacional_id' => $unidad->id,
                'fecha_asignacion_unidad' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Persona {$persona->name} {$persona->apellido} asignada exitosamente a la unidad {$unidad->numero_departamento}."
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al asignar persona a unidad: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar la persona a la unidad.'
            ], 500);
        }
    }

    /**
     * Desasignar una persona de una unidad habitacional
     */
    public function desasignarPersona($unidadId, $personaId)
    {
        try {
            $unidad = UnidadHabitacional::findOrFail($unidadId);
            $persona = Persona::where('id', $personaId)
                ->where('unidad_habitacional_id', $unidadId)
                ->firstOrFail();

            $nombrePersona = $persona->name . ' ' . $persona->apellido;

            $persona->update([
                'unidad_habitacional_id' => null,
                'fecha_asignacion_unidad' => null,
            ]);

            return redirect()->route('unidades.show', $unidadId)
                ->with('success', "La persona {$nombrePersona} ha sido desasignada exitosamente de la unidad {$unidad->numero_departamento}.");
        } catch (\Exception $e) {
            \Log::error('Error al desasignar persona de unidad: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al desasignar la persona de la unidad.');
        }
    }

    /**
     * Obtener personas disponibles para asignar a una unidad
     */
    public function personasDisponibles()
    {
        try {
            $personas = Persona::with('user')
                ->whereNull('unidad_habitacional_id')
                ->whereIn('estadoRegistro', ['Aceptado', 'Inactivo'])
                ->get();

            return response()->json([
                'success' => true,
                'personas' => $personas
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener personas disponibles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las personas disponibles.'
            ], 500);
        }
    }
}
