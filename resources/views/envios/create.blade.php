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
                                <select id="cliente_id" name="cliente_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Seleccionar cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre_completo }} - {{ $cliente->razon_social }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('cliente_id')" class="mt-2" />
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
</x-app-layout> 