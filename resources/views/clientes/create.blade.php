<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            {{ __('Nuevo Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-6 sm:mb-8">
                        <h3 class="text-base sm:text-lg font-medium">Crear Nuevo Cliente</h3>
                        <a href="{{ route('clientes.index') }}" class="inline-flex items-center justify-center bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                            <svg class="w-4 h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver
                        </a>
                    </div>

                    <form action="{{ route('clientes.store') }}" method="POST" class="space-y-6 sm:space-y-8">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                            <div>
                                <x-input-label for="asesor_comercial" :value="__('Asesor Comercial')" />
                                <x-text-input id="asesor_comercial" class="block mt-1 w-full" type="text" name="asesor_comercial" :value="old('asesor_comercial')" required autofocus />
                                <x-input-error :messages="$errors->get('asesor_comercial')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="razon_social" :value="__('Razón Social')" />
                                <x-text-input id="razon_social" class="block mt-1 w-full" type="text" name="razon_social" :value="old('razon_social')" required />
                                <x-input-error :messages="$errors->get('razon_social')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nombre_completo" :value="__('Nombre Completo')" />
                                <x-text-input id="nombre_completo" class="block mt-1 w-full" type="text" name="nombre_completo" :value="old('nombre_completo')" required />
                                <x-input-error :messages="$errors->get('nombre_completo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="puesto" :value="__('Puesto')" />
                                <x-text-input id="puesto" class="block mt-1 w-full" type="text" name="puesto" :value="old('puesto')" required />
                                <x-input-error :messages="$errors->get('puesto')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="celular" :value="__('Celular')" />
                                <x-text-input 
                                    id="celular" 
                                    class="block mt-1 w-full" 
                                    type="tel" 
                                    name="celular" 
                                    :value="old('celular')" 
                                    required 
                                    maxlength="10"
                                    pattern="[0-9]{10}"
                                    placeholder="10 dígitos"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                                    title="Ingresa exactamente 10 dígitos numéricos"
                                />
                                <x-input-error :messages="$errors->get('celular')" class="mt-2" />
                                <p class="text-xs text-gray-500 mt-1">Formato: 10 dígitos (ej: 9991234567)</p>
                            </div>

                            <div>
                                <x-input-label for="correo" :value="__('Correo')" />
                                <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo')" />
                                <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 mt-6 sm:mt-8">
                            <x-primary-button class="w-full sm:w-auto justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                {{ __('Crear Cliente') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 