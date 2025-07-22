<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Información del Cliente</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('clientes.edit', $cliente) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Editar
                            </a>
                            <a href="{{ route('clientes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Asesor Comercial</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $cliente->asesor_comercial }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Razón Social</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $cliente->razon_social }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Nombre Completo</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $cliente->nombre_completo }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Puesto</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $cliente->puesto }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Celular</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $cliente->celular }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Correo</h4>
                                <p class="mt-1 text-lg text-gray-900">{{ $cliente->correo }}</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                                <div>
                                    <span class="font-medium">Creado:</span> {{ $cliente->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div>
                                    <span class="font-medium">Última actualización:</span> {{ $cliente->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 