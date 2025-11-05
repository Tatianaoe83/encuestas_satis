<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes_activos = Cliente::latest()->get();
        $clientes_inactivos = Cliente::onlyTrashed()->latest('deleted_at')->get();
        $clientes = $clientes_activos->concat($clientes_inactivos);
        $total_clientes = $clientes->count();
        $total_activos = $clientes_activos->count();
        $total_inactivos = $clientes_inactivos->count();
        return view('clientes.index', compact('clientes', 'total_clientes', 'total_activos', 'total_inactivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $asesoresExistentes = Cliente::select('asesor_comercial')
            ->distinct()
            ->whereNotNull('asesor_comercial')
            ->orderBy('asesor_comercial')
            ->pluck('asesor_comercial')
            ->unique()
            ->values();
        
        return view('clientes.create', compact('asesoresExistentes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asesor_comercial' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
            'nombre_completo' => 'required|string|max:255',
            'puesto' => 'required|string|max:255',
            'celular' => 'required|string|size:10|regex:/^[0-9]+$/',
        ], [
            'celular.size' => 'El celular debe tener exactamente 10 dígitos.',
            'celular.regex' => 'El celular solo debe contener números.',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cliente = Cliente::withTrashed()->findOrFail($id);
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cliente = Cliente::withTrashed()->findOrFail($id);
        
        $asesoresExistentes = Cliente::select('asesor_comercial')
            ->distinct()
            ->whereNotNull('asesor_comercial')
            ->orderBy('asesor_comercial')
            ->pluck('asesor_comercial')
            ->unique()
            ->values();
        
        return view('clientes.edit', compact('cliente', 'asesoresExistentes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::withTrashed()->findOrFail($id);
        
        $request->validate([
            'asesor_comercial' => 'required|string|max:255',
            'razon_social' => 'required|string|max:255',
            'nombre_completo' => 'required|string|max:255',
            'puesto' => 'required|string|max:255',
            'celular' => 'required|string|size:10|regex:/^[0-9]+$/',
            'correo' => 'required|email|max:255',
        ], [
            'celular.size' => 'El celular debe tener exactamente 10 dígitos.',
            'celular.regex' => 'El celular solo debe contener números.',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Inactivate the specified resource (soft delete).
     */
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente inactivado exitosamente.');
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $cliente = Cliente::withTrashed()->findOrFail($id);
        $cliente->restore();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente reactivado exitosamente.');
    }
} 