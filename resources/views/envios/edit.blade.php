<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Envío') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Editar Envío</h3>
                        <a href="{{ route('envios.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver
                        </a>
                    </div>

                    <form action="{{ route('envios.update', $envio->idenvio) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="cliente_id" :value="__('Cliente')" />
                                <select id="cliente_id" name="cliente_id" class="select2-cliente block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
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
                                </select>
                                <x-input-error :messages="$errors->get('estado')" class="mt-2" />
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-blue-900 mb-4">📋 Preguntas del Envío</h4>
                                
                                <div class="space-y-4 text-sm">
                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 1 (Escala 0-10):</h5>
                                        <p class="text-gray-700">{{ $envio->pregunta_1 }}</p>
                                    </div>

                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 2 (Abierta):</h5>
                                        <p class="text-gray-700">{{ $envio->pregunta_2 }}</p>
                                    </div>

                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 3 (Opcional - Segmentación):</h5>
                                        <p class="text-gray-700">{{ $envio->pregunta_3 }}</p>
                                    </div>

                                    <div class="bg-white p-3 rounded border-l-4 border-blue-500">
                                        <h5 class="font-medium text-gray-900 mb-1">Pregunta 4 (Opcional - Mejoras):</h5>
                                        <p class="text-gray-700">{{ $envio->pregunta_4 }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-green-900 mb-4">✍️ Respuestas del Cliente</h4>
                                
                                <div class="space-y-4">
                                    <div>
                                        <x-input-label for="respuesta_1" :value="__('Respuesta 1 (Escala 0-10)')" />
                                        <x-text-input id="respuesta_1" class="block mt-1 w-full" type="text" name="respuesta_1" :value="old('respuesta_1', $envio->respuesta_1)" placeholder="Ej: 8" />
                                        <x-input-error :messages="$errors->get('respuesta_1')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="respuesta_2" :value="__('Respuesta 2 (Razón principal)')" />
                                        <textarea id="respuesta_2" name="respuesta_2" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Escriba la razón principal...">{{ old('respuesta_2', $envio->respuesta_2) }}</textarea>
                                        <x-input-error :messages="$errors->get('respuesta_2')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="respuesta_3" :value="__('Respuesta 3 (Tipo de obra)')" />
                                        <select id="respuesta_3" name="respuesta_3" class="select2-tipo-obra block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                            <option value="">Seleccionar tipo de obra</option>
                                            <option value="Vivienda unifamiliar" {{ old('respuesta_3', $envio->respuesta_3) == 'Vivienda unifamiliar' ? 'selected' : '' }}>Vivienda unifamiliar</option>
                                            <option value="Edificio o proyecto vertical" {{ old('respuesta_3', $envio->respuesta_3) == 'Edificio o proyecto vertical' ? 'selected' : '' }}>Edificio o proyecto vertical</option>
                                            <option value="Obra vial o infraestructura" {{ old('respuesta_3', $envio->respuesta_3) == 'Obra vial o infraestructura' ? 'selected' : '' }}>Obra vial o infraestructura</option>
                                            <option value="Obra industrial" {{ old('respuesta_3', $envio->respuesta_3) == 'Obra industrial' ? 'selected' : '' }}>Obra industrial</option>
                                            <option value="Otro" {{ old('respuesta_3', $envio->respuesta_3) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('respuesta_3')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="respuesta_4" :value="__('Respuesta 4 (Sugerencias de mejora)')" />
                                        <textarea id="respuesta_4" name="respuesta_4" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Escriba las sugerencias de mejora...">{{ old('respuesta_4', $envio->respuesta_4) }}</textarea>
                                        <x-input-error :messages="$errors->get('respuesta_4')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
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
        
        @if(old('respuesta_3'))
            var tipoObraValue = '{{ old('respuesta_3') }}';
        @elseif($envio->respuesta_3)
            var tipoObraValue = '{{ $envio->respuesta_3 }}';
        @endif
    </script>
</x-app-layout> 