<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Cliente') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Editar Cliente</h3>
                        <a href="{{ route('clientes.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Volver
                        </a>
                    </div>

                    <form action="{{ route('clientes.update', $cliente) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="asesor_comercial" :value="__('Asesor Comercial')" />
                                <x-text-input id="asesor_comercial" class="block mt-1 w-full" type="text" name="asesor_comercial" :value="old('asesor_comercial', $cliente->asesor_comercial)" required autofocus />
                                <x-input-error :messages="$errors->get('asesor_comercial')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="razon_social" :value="__('Razón Social')" />
                                <x-text-input id="razon_social" class="block mt-1 w-full" type="text" name="razon_social" :value="old('razon_social', $cliente->razon_social)" required />
                                <x-input-error :messages="$errors->get('razon_social')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="nombre_completo" :value="__('Nombre Completo')" />
                                <x-text-input id="nombre_completo" class="block mt-1 w-full" type="text" name="nombre_completo" :value="old('nombre_completo', $cliente->nombre_completo)" required />
                                <x-input-error :messages="$errors->get('nombre_completo')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="puesto" :value="__('Puesto')" />
                                <x-text-input id="puesto" class="block mt-1 w-full" type="text" name="puesto" :value="old('puesto', $cliente->puesto)" required />
                                <x-input-error :messages="$errors->get('puesto')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="celular" :value="__('Celular')" />
                                <x-text-input 
                                    id="celular" 
                                    class="block mt-1 w-full" 
                                    type="tel" 
                                    name="celular" 
                                    :value="old('celular', $cliente->celular)" 
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
                                <x-text-input id="correo" class="block mt-1 w-full" type="email" name="correo" :value="old('correo', $cliente->correo)"  />
                                <x-input-error :messages="$errors->get('correo')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="ml-3">
                                {{ __('Actualizar Cliente') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 