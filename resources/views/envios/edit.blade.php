    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Editar Envío') }}
            </h2>
        </x-slot>

        <div class="py-6 sm:py-8 lg:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                    <div class="p-4 sm:p-6 text-gray-900">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
                            <h3 class="text-base sm:text-lg font-medium">Editar Envío</h3>
                            <a href="{{ route('envios.index') }}" class="inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                        </div>

                        <form action="{{ route('envios.update', $envio->idenvio) }}" method="POST" class="space-y-4 sm:space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                                <div>
                                    <x-input-label for="cliente_id" :value="__('Cliente')" />
                                    <select id="cliente_id" name="cliente_id" class="select2-cliente block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required readonly>
                                        <option value="">Seleccionar cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ old('cliente_id', $envio->cliente_id) == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre_completo }} - {{ $cliente->razon_social }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="estado" :value="__('Estado')" />
                                    <select id="estado" name="estado" class="select2-estado block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="pendiente" {{ old('estado', $envio->estado) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="enviado" {{ old('estado', $envio->estado) == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                        <option value="respondido" {{ old('estado', $envio->estado) == 'respondido' ? 'selected' : '' }}>Respondido</option>
                                        <option value="cancelado" {{ old('estado', $envio->estado) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                        <option value="esperando_respuesta" {{ old('estado', $envio->estado) == 'esperando_respuesta' ? 'selected' : '' }}>Esperando respuesta</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                                </div>

                                <div class="bg-blue-50 p-3 sm:p-4 rounded-lg">
                                    <h4 class="text-base sm:text-lg font-medium text-blue-900 mb-3 sm:mb-4">📋 Preguntas del Envío</h4>
                                    
                                    <div class="space-y-4 text-sm">
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
                                </div>

                                <div class="bg-green-50 p-3 sm:p-4 rounded-lg">
                                    <h4 class="text-base sm:text-lg font-medium text-green-900 mb-3 sm:mb-4">✍️ Respuestas del Cliente</h4>
                                    
                                    <div class="space-y-4">
                                        <!-- Pregunta 1 - Subpreguntas 1.1 a 1.5 -->
                                        <div class="bg-white p-4 rounded border-l-4 border-blue-500">
                                            <h5 class="font-medium text-gray-900 mb-3">Pregunta 1 - Calificaciones (Escala 1-10)</h5>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <x-input-label for="respuesta_1_1" :value="__('1.1. Calidad del producto')" />
                                                                                                                                                    <x-text-input id="respuesta_1_1" class="block mt-1 w-full" type="number" min="1" max="10" name="respuesta_1_1" :value="old('respuesta_1_1', $envio->respuesta_1_1)" placeholder="1-10" />
                                                    <x-input-error :messages="$errors->get('respuesta_1_1')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="respuesta_1_2" :value="__('1.2. Puntualidad de entrega')" />
                                                    <x-text-input id="respuesta_1_2" class="block mt-1 w-full" type="number" min="1" max="10" name="respuesta_1_2" :value="old('respuesta_1_2', $envio->respuesta_1_2)" placeholder="1-10" />
                                                    <x-input-error :messages="$errors->get('respuesta_1_2')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="respuesta_1_3" :value="__('1.3. Trato del asesor comercial')" />
                                                    <x-text-input id="respuesta_1_3" class="block mt-1 w-full" type="number" min="1" max="10" name="respuesta_1_3" :value="old('respuesta_1_3', $envio->respuesta_1_3)" placeholder="1-10" />
                                                    <x-input-error :messages="$errors->get('respuesta_1_3')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="respuesta_1_4" :value="__('1.4. Precio')" />
                                                    <x-text-input id="respuesta_1_4" class="block mt-1 w-full" type="number" min="1" max="10" name="respuesta_1_4" :value="old('respuesta_1_4', $envio->respuesta_1_4)" placeholder="1-10" />
                                                    <x-input-error :messages="$errors->get('respuesta_1_4')" class="mt-2" />
                                                </div>

                                                <div>
                                                    <x-input-label for="respuesta_1_5" :value="__('1.5. Rapidez en programación')" />
                                                    <x-text-input id="respuesta_1_5" class="block mt-1 w-full" type="number" min="1" max="10" name="respuesta_1_5" :value="old('respuesta_1_5', $envio->respuesta_1_5)" placeholder="1-10" />
                                                    <x-input-error :messages="$errors->get('respuesta_1_5')" class="mt-2" />
                                                </div>

                                                <div class="bg-blue-50 p-3 rounded">
                                                                                                    <x-input-label for="promedio_respuesta_1" :value="__('Promedio Pregunta 1')" />
                                                    <x-text-input id="promedio_respuesta_1" class="block mt-1 w-full bg-blue-100" type="number" step="0.01" name="promedio_respuesta_1" :value="old('promedio_respuesta_1', $envio->promedio_respuesta_1)"/>
                                                    <p class="text-xs text-gray-600 mt-1">Calculado automáticamente</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Pregunta 2 - Recomendación -->
                                        <div class="bg-white p-4 rounded border-l-4 border-blue-500">
                                            <h5 class="font-medium text-gray-900 mb-3">Pregunta 2 - ¿Recomendarías a Konkret?</h5>
                                            <div>
                                                                                                <x-input-label for="respuesta_2" :value="__('Respuesta (Si/No)')" />
                                                <select id="respuesta_2" name="respuesta_2" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                    <option value="">Seleccionar respuesta</option>
                                                    <option value="Si" {{ old('respuesta_2', $envio->respuesta_2) == 'Si' ? 'selected' : '' }}>Si</option>
                                                    <option value="No" {{ old('respuesta_2', $envio->respuesta_2) == 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('respuesta_2')" class="mt-2" />
                                            </div>
                                        </div>

                                    <!-- Pregunta 3 - Mejoras (solo si respondió "No") -->
                                        @if(old('respuesta_2', $envio->respuesta_2) == 'No' || $envio->respuesta_3)
                                        <div class="bg-white p-4 rounded border-l-4 border-orange-500" data-pregunta="3">
                                            <h5 class="font-medium text-gray-900 mb-3">Pregunta 3 - ¿Qué podríamos hacer para mejorar tu experiencia?</h5>
                                            <div>
                                                <x-input-label for="respuesta_3" :value="__('Sugerencias de mejora')" />
                                                <textarea id="respuesta_3" name="respuesta_3" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Escriba las sugerencias de mejora...">{{ old('respuesta_3', $envio->respuesta_3) }}</textarea>
                                                <x-input-error :messages="$errors->get('respuesta_3')" class="mt-2" />
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-4 sm:mt-6">
                                <x-primary-button class="w-full sm:w-auto justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ __('Actualizar Envío') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Variables para restaurar valores después de errores de validación
            @if(old('cliente_id'))
                var clienteIdValue = '{{ old('cliente_id') }}';
            @elseif($envio->cliente_id)
                var clienteIdValue = '{{ $envio->cliente_id }}';
            @endif
            
            @if(old('estado'))
                var estadoValue = '{{ old('estado') }}';
            @elseif($envio->estado)
                var estadoValue = '{{ $envio->estado }}';
            @endif

                    // Función para mostrar/ocultar la pregunta 3 basada en la respuesta de la pregunta 2
            function togglePregunta3() {
                const respuesta2 = document.getElementById('respuesta_2');
                const pregunta3Container = document.querySelector('[data-pregunta="3"]');
                
                if (respuesta2.value === 'No') {
                    if (pregunta3Container) {
                        pregunta3Container.style.display = 'block';
                    }
                } else {
                    if (pregunta3Container) {
                        pregunta3Container.style.display = 'none';
                        // Limpiar el campo si se oculta
                        const respuesta3Input = document.getElementById('respuesta_3');
                        if (respuesta3Input) {
                            respuesta3Input.value = '';
                        }
                    }
                }
            }

            // Agregar event listener para la pregunta 2
            document.addEventListener('DOMContentLoaded', function() {
                const respuesta2 = document.getElementById('respuesta_2');
                if (respuesta2) {   
                    respuesta2.addEventListener('change', togglePregunta3);
                    // Ejecutar al cargar la página
                    togglePregunta3();
                }
            });
        </script>
    </x-app-layout> 