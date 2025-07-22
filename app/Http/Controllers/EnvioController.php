<?php

namespace App\Http\Controllers;

use App\Models\Envio;
use App\Models\Cliente;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $envios = Envio::with('cliente')->latest()->paginate(10);
        return view('envios.index', compact('envios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::all();
        return view('envios.create', compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
        ]);

        // Crear el envío con las preguntas por defecto
        $envio = Envio::create([
            'cliente_id' => $request->cliente_id,
            'pregunta_1' => 'En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?',
            'pregunta_2' => '¿Cuál es la razón principal de tu calificación?',
            'pregunta_3' => '¿A qué tipo de obra se destinó este concreto?',
            'pregunta_4' => '¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?',
            'estado' => 'pendiente',
        ]);

        return redirect()->route('envios.index')
            ->with('success', 'Envío creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Envio $envio)
    {
        $envio->load('cliente');
        return view('envios.show', compact('envio'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Envio $envio)
    {
        $clientes = Cliente::all();
        return view('envios.edit', compact('envio', 'clientes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Envio $envio)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'estado' => 'required|in:pendiente,enviado,respondido,cancelado',
            'respuesta_1' => 'nullable|string|max:1000',
            'respuesta_2' => 'nullable|string|max:1000',
            'respuesta_3' => 'nullable|string|max:1000',
            'respuesta_4' => 'nullable|string|max:1000',
        ]);

        $envio->update($request->all());

        return redirect()->route('envios.index')
            ->with('success', 'Envío actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Envio $envio)
    {
        $envio->delete();

        return redirect()->route('envios.index')
            ->with('success', 'Envío eliminado exitosamente.');
    }

    /**
     * Marcar envío como enviado.
     */
    public function marcarEnviado(Envio $envio)
    {
        $envio->update([
            'estado' => 'enviado',
            'fecha_envio' => now(),
        ]);

        return redirect()->route('envios.index')
            ->with('success', 'Envío marcado como enviado.');
    }

    /**
     * Marcar envío como respondido.
     */
    public function marcarRespondido(Envio $envio)
    {
        $envio->update([
            'estado' => 'respondido',
            'fecha_respuesta' => now(),
        ]);

        return redirect()->route('envios.index')
            ->with('success', 'Envío marcado como respondido.');
    }
} 