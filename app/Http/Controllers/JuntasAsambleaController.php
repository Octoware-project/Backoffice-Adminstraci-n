<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JuntasAsamblea;

class JuntasAsambleaController extends Controller
{
    public function index(Request $request)
    {
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
    }

    public function create()
    {
    return view('admin.asamblea.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'lugar' => 'required|string|max:255',
            'fecha' => 'required|date',
            'detalle' => 'nullable|string',
        ]);
        \App\Models\JuntasAsamblea::create($request->all());
        return redirect()->route('admin.asamblea.index')->with('success', 'Junta creada correctamente.');
    }

    public function edit($id)
    {
        $junta = JuntasAsamblea::findOrFail($id);
    return view('admin.asamblea.edit', compact('junta'));
    }


    public function update(Request $request, $id)
    {
        $junta = JuntasAsamblea::findOrFail($id);
        $request->validate([
            'lugar' => 'required|string|max:255',
            'fecha' => 'required|date',
            'detalle' => 'nullable|string',
        ]);
        $junta->update($request->all());
        return redirect()->route('admin.asamblea.index')->with('success', 'Junta actualizada correctamente.');
    }


    public function destroy($id)
    {
        $junta = JuntasAsamblea::findOrFail($id);
        $junta->delete();
        return redirect()->route('admin.asamblea.index')->with('success', 'Junta eliminada correctamente.');
    }

    public function vistaAsamblea(Request $request)
    {
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
    }

    public function show($id)
    {
        $junta = \App\Models\JuntasAsamblea::findOrFail($id);
    return view('admin.asamblea.show', compact('junta'));
    }
}
