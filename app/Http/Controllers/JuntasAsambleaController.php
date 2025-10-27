<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JuntasAsamblea;

class JuntasAsambleaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = \App\Models\JuntasAsamblea::query();

            if ($request->filled('filter_mes')) {
                $query->whereMonth('fecha', $request->filter_mes);
            }

            if ($request->filled('filter_anio')) {
                $query->whereYear('fecha', $request->filter_anio);
            }

            $sortField = $request->get('sort_field', 'fecha');
            $sortDirection = $request->get('sort_direction', 'desc');
            
            $allowedSortFields = ['fecha', 'lugar', 'created_at'];
            if (!in_array($sortField, $allowedSortFields)) {
                $sortField = 'fecha';
            }
            
            $query->orderBy($sortField, $sortDirection);

            $juntas = $query->get();
            
            return view('admin.asamblea.Asamblea', compact('juntas'));
        } catch (\Exception $e) {
            \Log::error('Error al listar juntas de asamblea: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al cargar las juntas de asamblea.');
        }
    }

    public function create()
    {
        try {
            return view('admin.asamblea.create');
        } catch (\Exception $e) {
            \Log::error('Error al mostrar formulario de creación: ' . $e->getMessage());
            return redirect()->route('admin.asamblea.index')->with('error', 'Error al cargar el formulario.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'lugar' => 'required|string|max:255',
                'fecha' => 'required|date',
                'detalle' => 'nullable|string',
            ]);
            
            \App\Models\JuntasAsamblea::create($request->all());
            
            return redirect()->route('admin.asamblea.index')->with('success', 'Junta creada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);
        } catch (\Exception $e) {
            \Log::error('Error al crear junta de asamblea: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la junta.');
        }
    }

    public function edit($id)
    {
        try {
            $junta = JuntasAsamblea::findOrFail($id);
            return view('admin.asamblea.edit', compact('junta'));
        } catch (\Exception $e) {
            \Log::error('Error al editar junta de asamblea: ' . $e->getMessage());
            return redirect()->route('admin.asamblea.index')->with('error', 'Junta no encontrada.');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $junta = JuntasAsamblea::findOrFail($id);
            
            $request->validate([
                'lugar' => 'required|string|max:255',
                'fecha' => 'required|date',
                'detalle' => 'nullable|string',
            ]);
            
            $junta->update($request->all());
            
            return redirect()->route('admin.asamblea.index')->with('success', 'Junta actualizada correctamente.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar junta de asamblea: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la junta.');
        }
    }


    public function destroy($id)
    {
        try {
            $junta = JuntasAsamblea::findOrFail($id);
            $junta->delete();
            
            return redirect()->route('admin.asamblea.index')->with('success', 'Junta eliminada correctamente.');
        } catch (\Exception $e) {
            \Log::error('Error al eliminar junta de asamblea: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al eliminar la junta.');
        }
    }

    public function vistaAsamblea(Request $request)
    {
        return $this->index($request);
    }

    public function show($id)
    {
        try {
            $junta = \App\Models\JuntasAsamblea::findOrFail($id);
            return view('admin.asamblea.show', compact('junta'));
        } catch (\Exception $e) {
            \Log::error('Error al mostrar junta de asamblea: ' . $e->getMessage());
            return redirect()->route('admin.asamblea.index')->with('error', 'Junta no encontrada.');
        }
    }
}
