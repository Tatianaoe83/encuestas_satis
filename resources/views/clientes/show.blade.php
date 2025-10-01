<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Detalles del Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-4 sm:mb-6">
                        <h3 class="text-base sm:text-lg font-medium">Información del Cliente</h3>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center justify-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </a>
                            <a href="{{ route('clientes.index') }}" class="inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                                <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 sm:p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div>
                                <h4 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Asesor Comercial</h4>
                                <p class="mt-1 text-base sm:text-lg text-gray-900 break-words">{{ $cliente->asesor_comercial ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Razón Social</h4>
                                <p class="mt-1 text-base sm:text-lg text-gray-900 break-words">{{ $cliente->razon_social ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Nombre Completo</h4>
                                <p class="mt-1 text-base sm:text-lg text-gray-900 break-words">{{ $cliente->nombre_completo ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Puesto</h4>
                                <p class="mt-1 text-base sm:text-lg text-gray-900 break-words">{{ $cliente->puesto ?? 'N/A' }}</p>
                            </div>

                            <div>
                                <h4 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Celular</h4>
                                <p class="mt-1 text-base sm:text-lg text-gray-900">
                                    <a href="tel:{{ $cliente->celular }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $cliente->celular ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>

                            <div>
                                <h4 class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide">Correo</h4>
                                <p class="mt-1 text-base sm:text-lg text-gray-900 break-all">
                                    <a href="mailto:{{ $cliente->correo }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $cliente->correo ?? 'N/A' }}
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 text-xs sm:text-sm text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium mr-1">Creado:</span> {{ $cliente->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span class="font-medium mr-1">Actualizado:</span> {{ $cliente->updated_at ? $cliente->updated_at->format('d/m/Y H:i') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 