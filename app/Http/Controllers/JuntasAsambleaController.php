<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JuntasAsamblea;

class JuntasAsambleaController extends Controller
{
    public function index()
    {
        $juntas = \App\Models\JuntasAsamblea::all();
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

    public function vistaAsamblea()
    {
        $juntas = \App\Models\JuntasAsamblea::all();
        return view('admin.asamblea.Asamblea', compact('juntas'));
    }

    public function show($id)
    {
        $junta = \App\Models\JuntasAsamblea::findOrFail($id);
    return view('admin.asamblea.show', compact('junta'));
    }
}
