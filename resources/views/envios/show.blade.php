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
                            <a href="{{ route('envios.edit', $envio->idenvio) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            <a href="{{ route('envios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
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
                                            @case('esperando_respuesta')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    Esperando respuesta
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
                                            @case('completado')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Completado
                                                </span>
                                                @break
                                            @default
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ ucfirst($envio->estado) }}
                                                </span>
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

                        <!-- Información de WhatsApp -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">📱 Información de WhatsApp</h4>
                            <div class="space-y-3">
                                @if($envio->whatsapp_number)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Número WhatsApp:</span>
                                        <p class="text-gray-900">{{ $envio->whatsapp_number }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Message SID:</span>
                                        <p class="text-gray-900 text-xs font-mono">{{ $envio->twilio_message_sid }}</p>
                                    </div>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500">Enviado por WhatsApp:</span>
                                        <p class="text-gray-900">{{ $envio->whatsapp_sent_at ? $envio->whatsapp_sent_at->format('d/m/Y H:i') : 'No enviado' }}</p>
                                    </div>
                                    @if($envio->whatsapp_responded_at)
                                        <div>
                                            <span class="text-sm font-medium text-gray-500">Respondido por WhatsApp:</span>
                                            <p class="text-gray-900">{{ $envio->whatsapp_responded_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-4">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <p class="text-gray-500">No enviado por WhatsApp</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Preguntas y Respuestas -->
                    <div class="mt-6 bg-gray-50 p-6 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">📋 Preguntas del Envío</h4>
                        <div class="space-y-4 text-sm mb-6">
                            <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                <h5 class="font-medium text-gray-900 mb-1">Pregunta 1 (Escala 1-10):</h5>
                                <p class="text-gray-700">En una escala del 1-10, ¿Cómo calificarías nuestro servicio con base en los siguientes puntos?</p>
                                <p class="text-gray-600 mt-2 text-xs italic">Se envió una pregunta por una para calificar cada punto:</p>
                                <ul class="text-gray-600 mt-1 ml-4 list-disc text-xs">
                                    <li>1.1. Calidad del producto</li>
                                    <li>1.2. Puntualidad de entrega</li>
                                    <li>1.3. Trato del asesor comercial</li>
                                    <li>1.4. Precio</li>
                                    <li>1.5. Rapidez en programación</li>
                                </ul>
                            </div>

                            <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                <h5 class="font-medium text-gray-900 mb-1">Pregunta 2 (Si/No):</h5>
                                <p class="text-gray-700">¿Recomendarías a Konkret?</p>
                            </div>

                            <div class="bg-white p-3 rounded border-l-4 border-orange-500">
                                <h5 class="font-medium text-gray-900 mb-1">Pregunta 3 (Opcional - Abierta):</h5>
                                <p class="text-gray-700">¿Qué podríamos hacer para mejorar tu experiencia?</p>
                                <p class="text-gray-600 mt-1 text-xs italic">Solo se muestra si responde "No" a la pregunta 2</p>
                            </div>
                        </div>

                        <h4 class="text-lg font-medium text-gray-900 mb-4">✍️ Respuestas del Cliente</h4>
                        <div class="space-y-4">
                            <!-- Pregunta 1 - Subpreguntas 1.1 a 1.5 -->
                            <div class="bg-white p-4 rounded border-l-4 border-blue-500">
                                <h5 class="font-medium text-gray-900 mb-3">Pregunta 1 - Calificaciones (Escala 1-10)</h5>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                         <div class="bg-gray-50 p-3 rounded">
                                         <span class="text-sm font-medium text-gray-500">1.1. Calidad del producto:</span>
                                         @if($envio->respuesta_1_1)
                                             <p class="text-gray-900 font-semibold text-lg">{{ $envio->respuesta_1_1 }}/10</p>
                                         @else
                                             <p class="text-gray-500 italic">Sin respuesta</p>
                                         @endif
                                     </div>

                                     <div class="bg-gray-50 p-3 rounded">
                                         <span class="text-sm font-medium text-gray-500">1.2. Puntualidad de entrega:</span>
                                         @if($envio->respuesta_1_2)
                                             <p class="text-gray-900 font-semibold text-lg">{{ $envio->respuesta_1_2 }}/10</p>
                                         @else
                                             <p class="text-gray-500 italic">Sin respuesta</p>
                                         @endif
                                     </div>

                                     <div class="bg-gray-50 p-3 rounded">
                                         <span class="text-sm font-medium text-gray-500">1.3. Trato del asesor comercial:</span>
                                         @if($envio->respuesta_1_3)
                                             <p class="text-gray-900 font-semibold text-lg">{{ $envio->respuesta_1_3 }}/10</p>
                                         @else
                                             <p class="text-gray-500 italic">Sin respuesta</p>
                                         @endif
                                     </div>

                                     <div class="bg-gray-50 p-3 rounded">
                                         <span class="text-sm font-medium text-gray-500">1.4. Precio:</span>
                                         @if($envio->respuesta_1_4)
                                             <p class="text-gray-900 font-semibold text-lg">{{ $envio->respuesta_1_4 }}/10</p>
                                         @else
                                             <p class="text-gray-500 italic">Sin respuesta</p>
                                         @endif
                                     </div>

                                     <div class="bg-gray-50 p-3 rounded">
                                         <span class="text-sm font-medium text-gray-500">1.5. Rapidez en programación:</span>
                                         @if($envio->respuesta_1_5)
                                             <p class="text-gray-900 font-semibold text-lg">{{ $envio->respuesta_1_5 }}/10</p>
                                         @else
                                             <p class="text-gray-500 italic">Sin respuesta</p>
                                         @endif
                                     </div>

                                     <div class="bg-blue-50 p-3 rounded border-l-4 border-blue-500">
                                         <span class="text-sm font-medium text-blue-700">Promedio Pregunta 1:</span>
                                         @if($envio->promedio_respuesta_1)
                                             <p class="text-blue-900 font-bold text-xl">{{ number_format($envio->promedio_respuesta_1, 2) }}/10</p>
                                         @else
                                             <p class="text-blue-500 italic">No calculado</p>
                                         @endif
                                     </div>
                                </div>
                            </div>

                                                         <!-- Pregunta 2 - Recomendación -->
                             <div class="bg-white p-4 rounded border-l-4 border-blue-500">
                                 <h5 class="font-medium text-gray-900 mb-3">Pregunta 2 - ¿Recomendarías a Konkret?</h5>
                                 @if($envio->respuesta_2)
                                     <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                         <span class="text-sm font-medium text-green-700">Respuesta:</span>
                                         <p class="text-green-900 font-semibold text-lg">{{ $envio->respuesta_2 }}</p>
                                     </div>
                                 @else
                                     <p class="text-gray-500 italic">Sin respuesta</p>
                                 @endif
                             </div>

                             <!-- Pregunta 3 - Mejoras (solo si respondió "No") -->
                             @if($envio->respuesta_2 == 'No' && $envio->respuesta_3)
                             <div class="bg-white p-4 rounded border-l-4 border-orange-500">
                                 <h5 class="font-medium text-gray-900 mb-3">Pregunta 3 - ¿Qué podríamos hacer para mejorar tu experiencia?</h5>
                                 <div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                                     <span class="text-sm font-medium text-green-700">Sugerencias de mejora:</span>
                                     <p class="text-green-900">{{ $envio->respuesta_3 }}</p>
                                 </div>
                             </div>
                             @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 