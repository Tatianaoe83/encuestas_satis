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
        $clientes = Cliente::latest()->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
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
            'correo' => 'required|email|max:255',
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
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
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
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente eliminado exitosamente.');
    }
} 