<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Envío') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Crear Nuevo Envío</h3>
                        <a href="{{ route('envios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver
                        </a>
                    </div>

                    <form action="{{ route('envios.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="cliente_id" :value="__('Cliente')" />
                                <select id="cliente_id" name="cliente_id" class="select2-cliente block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccionar cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }} data-celular="{{ $cliente->celular }}">
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

                            <div id="info-cliente" class="bg-blue-50 p-4 rounded-lg hidden">
                                <h4 class="text-lg font-medium text-blue-900 mb-2">📱 Información del Cliente</h4>
                                <div id="cliente-info-content" class="text-sm text-blue-800">
                                    <!-- La información del cliente se mostrará aquí -->
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-blue-900 mb-4">📋 Preguntas que se enviarán al cliente</h4>
                                
                                <div class="space-y-4 text-sm">
                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 1 (Escala 0-10):</h5>
                                        <p class="text-gray-700">En una escala del 0 al 10, ¿qué probabilidad hay de que recomiende proser a un colega o contacto del sector construcción?</p>
                                    </div>

                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 2 (Abierta):</h5>
                                        <p class="text-gray-700">¿Cuál es la razón principal de tu calificación?</p>
                                    </div>

                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 3 (Opcional - Segmentación):</h5>
                                        <p class="text-gray-700">¿A qué tipo de obra se destinó este concreto?</p>
                                        <ul class="text-gray-600 mt-1 ml-4 list-disc">
                                            <li>Vivienda unifamiliar</li>
                                            <li>Edificio o proyecto vertical</li>
                                            <li>Obra vial o infraestructura</li>
                                            <li>Obra industrial</li>
                                            <li>Otro (especificar)</li>
                                        </ul>
                                    </div>

                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 4 (Opcional - Mejoras):</h5>
                                        <p class="text-gray-700">¿Qué podríamos hacer para mejorar tu experiencia en futuras entregas?</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
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