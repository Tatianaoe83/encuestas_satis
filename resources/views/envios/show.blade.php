<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Envío') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Información del Envío</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('envios.edit', $envio) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            <a href="{{ route('envios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Información del Cliente -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Información del Cliente</h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Nombre Completo:</span>
                                    <p class="text-gray-900">{{ $envio->cliente->nombre_completo }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Razón Social:</span>
                                    <p class="text-gray-900">{{ $envio->cliente->razon_social }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Asesor Comercial:</span>
                                    <p class="text-gray-900">{{ $envio->cliente->asesor_comercial }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Puesto:</span>
                                    <p class="text-gray-900">{{ $envio->cliente->puesto }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Celular:</span>
                                    <p class="text-gray-900">{{ $envio->cliente->celular }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Correo:</span>
                                    <p class="text-gray-900">{{ $envio->cliente->correo }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Estado y Fechas -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Estado y Fechas</h4>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Estado:</span>
                                    <div class="mt-1">
                                        @switch($envio->estado)
                                            @case('pendiente')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                                @break
                                            @case('enviado')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Enviado
                                                </span>
                                                @break
                                            @case('respondido')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Respondido
                                                </span>
                                                @break
                                            @case('cancelado')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Cancelado
                                                </span>
                                                @break
                                        @endswitch
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Fecha de Creación:</span>
                                    <p class="text-gray-900">{{ $envio->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Fecha de Envío:</span>
                                    <p class="text-gray-900">{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : 'No enviado' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Fecha de Respuesta:</span>
                                    <p class="text-gray-900">{{ $envio->fecha_respuesta ? $envio->fecha_respuesta->format('d/m/Y H:i') : 'Sin respuesta' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preguntas y Respuestas -->
                    <div class="mt-6 bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Preguntas y Respuestas del Envío</h4>
                        <div class="space-y-4">
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-medium text-gray-900 mb-2">Pregunta 1 (Escala 0-10):</h5>
                                <p class="text-gray-700 mb-3">{{ $envio->pregunta_1 }}</p>
                                @if($envio->respuesta_1)
                                    <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                        <h6 class="font-medium text-green-900 mb-1">Respuesta:</h6>
                                        <p class="text-green-800">{{ $envio->respuesta_1 }}</p>
                                    </div>
                                @else
                                    <p class="text-gray-500 italic">Sin respuesta</p>
                                @endif
                            </div>
                            
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-medium text-gray-900 mb-2">Pregunta 2 (Abierta):</h5>
                                <p class="text-gray-700 mb-3">{{ $envio->pregunta_2 }}</p>
                                @if($envio->respuesta_2)
                                    <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                        <h6 class="font-medium text-green-900 mb-1">Respuesta:</h6>
                                        <p class="text-green-800">{{ $envio->respuesta_2 }}</p>
                                    </div>
                                @else
                                    <p class="text-gray-500 italic">Sin respuesta</p>
                                @endif
                            </div>
                            
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-medium text-gray-900 mb-2">Pregunta 3 (Opcional - Segmentación):</h5>
                                <p class="text-gray-700 mb-3">{{ $envio->pregunta_3 }}</p>
                                <ul class="text-gray-600 mb-3 ml-4 list-disc">
                                    <li>Vivienda unifamiliar</li>
                                    <li>Edificio o proyecto vertical</li>
                                    <li>Obra vial o infraestructura</li>
                                    <li>Obra industrial</li>
                                    <li>Otro (especificar)</li>
                                </ul>
                                @if($envio->respuesta_3)
                                    <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                        <h6 class="font-medium text-green-900 mb-1">Respuesta:</h6>
                                        <p class="text-green-800">{{ $envio->respuesta_3 }}</p>
                                    </div>
                                @else
                                    <p class="text-gray-500 italic">Sin respuesta</p>
                                @endif
                            </div>
                            
                            <div class="bg-white p-4 rounded border">
                                <h5 class="font-medium text-gray-900 mb-2">Pregunta 4 (Opcional - Mejoras):</h5>
                                <p class="text-gray-700 mb-3">{{ $envio->pregunta_4 }}</p>
                                @if($envio->respuesta_4)
                                    <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                        <h6 class="font-medium text-green-900 mb-1">Respuesta:</h6>
                                        <p class="text-green-800">{{ $envio->respuesta_4 }}</p>
                                    </div>
                                @else
                                    <p class="text-gray-500 italic">Sin respuesta</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 