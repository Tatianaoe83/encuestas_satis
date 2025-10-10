<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Envío') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6 sm:mb-8">
                        <h3 class="text-base sm:text-lg font-medium">Crear Nuevo Envío</h3>
                        <a href="{{ route('envios.index') }}" class="inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </a>
                    </div>

                    <form action="{{ route('envios.store') }}" method="POST" class="space-y-6 sm:space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 gap-6 sm:gap-8">
                            <div>
                                <x-input-label for="cliente_id" :value="__('Cliente')" />
                                <select id="cliente_id" name="cliente_id" class="select2-cliente block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccionar cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->idcliente }}" {{ old('cliente_id') == $cliente->idcliente ? 'selected' : '' }} data-celular="{{ $cliente->celular }}">
                                            {{ $cliente->nombre_completo }} - {{ $cliente->razon_social }}
                                            @if($cliente->celular)
                                                ({{ $cliente->celular }})
                                            @else
                                                (Sin celular)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
                            </div>

                            <div id="info-cliente" class="bg-blue-50 p-4 sm:p-6 rounded-lg hidden">
                                <h4 class="text-base sm:text-lg font-medium text-blue-900 mb-2">📱 Información del Cliente</h4>
                                <div id="cliente-info-content" class="text-sm text-blue-800">
                                    <!-- La información del cliente se mostrará aquí -->
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 sm:p-6 rounded-lg">
                                <h4 class="text-base sm:text-lg font-medium text-blue-900 mb-3 sm:mb-4">📋 Preguntas que se enviarán al cliente</h4>
                                
                                <div class="space-y-6 text-sm">
                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 1 (Escala 1-10):</h5>
                                        <p class="text-gray-700">En una escala del 1-10, ¿Cómo calificarías nuestro servicio con base en los siguientes puntos?</p>
                                        <p class="text-gray-600 mt-2 text-xs italic">Se enviará una pregunta por una para calificar cada punto:</p>
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
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-6 sm:mt-8">
                            <x-primary-button class="w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                {{ __('Crear Envío') }}
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
        @endif

        // Mostrar información del cliente cuando se seleccione
        document.getElementById('cliente_id').addEventListener('change', function() {
            const select = this;
            const infoCliente = document.getElementById('info-cliente');
            const infoContent = document.getElementById('cliente-info-content');
            
            if (select.value) {
                const selectedOption = select.options[select.selectedIndex];
                const celular = selectedOption.getAttribute('data-celular');
                const nombre = selectedOption.text.split(' - ')[0];
                
                if (celular) {
                    infoContent.innerHTML = `
                        <p><strong>Nombre:</strong> ${nombre}</p>
                        <p><strong>Celular:</strong> ${celular}</p>
                        <p class="text-green-600 font-medium">✅ Listo para enviar por WhatsApp</p>
                    `;
                } else {
                    infoContent.innerHTML = `
                        <p><strong>Nombre:</strong> ${nombre}</p>
                        <p class="text-red-600 font-medium">⚠️ El cliente no tiene número de celular registrado</p>
                        <p class="text-sm">Necesitas agregar un número de celular al cliente para poder enviar la encuesta por WhatsApp.</p>
                    `;
                }
                
                infoCliente.classList.remove('hidden');
            } else {
                infoCliente.classList.add('hidden');
            }
        });

        // Mostrar información inicial si hay un cliente seleccionado
        @if(old('cliente_id'))
            document.getElementById('cliente_id').dispatchEvent(new Event('change'));
        @endif
    </script>
</x-app-layout> 