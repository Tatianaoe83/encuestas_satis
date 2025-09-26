<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Gestión de Envíos') }}
                </h2>
                <p class="text-gray-600 mt-1">Administra el envío de encuestas a tus clientes</p>
            </div>
            <div class="flex items-center space-x-3">
               
                <a href="{{ route('envios.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Nuevo Envío
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Envíos</p>
                            <p class="text-2xl font-bold mt-1">{{ $envios->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z" clip-rule="evenodd"/>
                              </svg>
                              
                        </div>
                    </div>
                </div>

                <div class="bg-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Completados</p>
                            <p class="text-2xl font-bold mt-1">{{ $envios->where('estado', 'completado')->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3 5.983C3 4.888 3.895 4 5 4h14c1.105 0 2 .888 2 1.983v8.923a1.992 1.992 0 0 1-2 1.983h-6.6l-2.867 2.7c-.955.899-2.533.228-2.533-1.08v-1.62H5c-1.105 0-2-.888-2-1.983V5.983Zm5.706 3.809a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Zm2.585.002a1 1 0 1 1 .003 1.414 1 1 0 0 1-.003-1.414Zm5.415-.002a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Z" clip-rule="evenodd"/>
                              </svg>
                              
                              
                              
                        </div>
                    </div>
                </div>

                <div class="bg-red-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-red-100 text-sm font-medium">Cancelados</p>
                            <p class="text-2xl font-bold mt-1">{{ $envios->where('estado', 'cancelado')->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                              </svg>
                              
                              
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Pendientes</p>
                            <p class="text-2xl font-bold mt-1">{{ $envios->where('estado', 'pendiente')->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                              </svg>
                              
                        </div>
                    </div>
                </div>
            </div>

            <!-- Saldo de Twilio -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Saldo de Twilio
                    </h2>
                    <button onclick="actualizarSaldoTwilio()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Actualizar
                    </button>
                </div>

                @if($saldoTwilio['success'])
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Saldo Actual -->
                        <div class="bg-purple-600 from-purple-600 to-purple-700 rounded-lg p-4 shadow-lg text-purple-800">
                            <div class="flex items-center justify-between text-purple-800">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Saldo Actual</p>
                                    <p class="text-2xl font-bold mt-1 text-purple-100">{{ $saldoTwilio['balance_formatted'] }}</p>
                                </div>
                                <div class="bg-white bg-opacity-20 rounded-full p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Cuenta -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Información de Cuenta</h3>
                            <div class="space-y-1">
                               
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">Nombre:</span> {{ $saldoTwilio['account_name'] }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    <span class="font-medium">Estado:</span> 
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($saldoTwilio['account_status']) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Moneda -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Moneda</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $saldoTwilio['currency'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">Código de moneda</p>
                        </div>

                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <div>
                                <h3 class="text-sm font-medium text-red-800">Error al obtener saldo</h3>
                                <p class="text-sm text-red-600 mt-1">{{ $saldoTwilio['error'] }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Mensajes de éxito y error -->
            @if(session('success'))
                <div class="bg-green-500 border border-green-400 text-white px-6 py-4 rounded-xl mb-6 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500 border border-red-400 text-white px-6 py-4 rounded-xl mb-6 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Tabla de envíos mejorada -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            

                <div class="p-6">
                    <table class="w-full display responsive nowrap" id="tabla-envios" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th>Fechas</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($envios as $envio)
                                <tr>
                                    <td class="px-6 py-4">{{ $envio->idenvio }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center mr-4">
                                                <span class="text-white font-semibold text-sm">{{ strtoupper(substr($envio->cliente->nombre_completo, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $envio->cliente->nombre_completo }}</div>
                                                <div class="text-sm text-gray-500">{{ $envio->cliente->razon_social }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($envio->estado)
                                            @case('pendiente')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Pendiente
                                                </span>
                                                @break
                                            @case('enviado')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                    </svg>
                                                    Enviado
                                                </span>
                                                @break
                                            @case('respondido')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                    Respondido
                                                </span>
                                                @break
                                            @case('cancelado')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Cancelado
                                                </span>
                                                @break
                                            @case('en_proceso')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    En proceso
                                                </span>
                                                @break
                                            @case('completado')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                    </svg>
                                                    Completado
                                                </span>
                                                @break
                                            @case('esperando_respuesta')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Esperando respuesta
                                                </span>
                                                @break

                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">Creado:</span> {{ $envio->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            @if($envio->fecha_envio)
                                                <div class="text-sm text-gray-500">
                                                    <span class="font-medium">Enviado:</span> {{ $envio->fecha_envio->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                            @if($envio->fecha_respuesta)
                                                <div class="text-sm text-green-600">
                                                    <span class="font-medium">Respondido:</span> {{ $envio->fecha_respuesta->format('d/m/Y H:i') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('envios.show', $envio->idenvio) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                            <a href="{{ route('envios.edit', $envio->idenvio) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>
                                            
                                            <!-- Botón para enlace de encuesta web -->
                                            <button onclick="copiarEnlaceEncuesta({{ $envio->idenvio }})" 
                                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors duration-200"
                                                    title="Copiar enlace de encuesta web">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                                Enlace Web
                                            </button>
                                            
                                            @if($envio->estado == 'pendiente')
                                    
                                                
                                                @if($envio->cliente->celular)
                                                    <form action="{{ route('envios.enviar-por-whatsapp', $envio->idenvio) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200 transition-colors duration-200">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                            </svg>
                                                            WhatsApp
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-gray-500 bg-gray-100">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                        </svg>
                                                        Sin celular
                                                    </span>
                                                @endif
                                            @endif
                                            
                                            @if($envio->estado === 'enviado')
                                                <form action="{{ route('envios.marcar-respondido', $envio->idenvio) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-purple-700 bg-purple-100 hover:bg-purple-200 transition-colors duration-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                        </svg>
                                                        Respondido
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ route('envios.destroy', $envio->idenvio) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este envío?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay envíos registrados</h3>
                                            <p class="text-gray-500 mb-4">Comienza creando tu primer envío de encuesta</p>
                                            <a href="{{ route('envios.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                                                Crear Envío
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                    <!-- Script para inicializar DataTables -->
            <script>
                $(document).ready(function() {
                    // Verificar que la tabla existe y tiene el número correcto de columnas
                    var table = $('#tabla-envios');
                    var headerCount = table.find('thead th').length;
                    var firstRowCount = table.find('tbody tr:first td').length;
                    
                    console.log('Columnas en encabezado:', headerCount);
                    console.log('Columnas en primera fila:', firstRowCount);
                    
                    if (headerCount !== firstRowCount) {
                        console.error('Error: Número de columnas no coincide');
                        return;
                    }
                    
                    $('#tabla-envios').DataTable({
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                        },
                        pageLength: 10,
                        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                        order: [[0, 'desc']],
                        columnDefs: [
                            {
                                targets: -1,
                                orderable: false,
                                searchable: false
                            },
                            {
                                targets: [1, 2, 3, 4],
                                orderable: false
                            }
                        ],
                        autoWidth: false,
                        processing: true,
                        deferRender: true,
                        destroy: true,
                        initComplete: function() {
                            // Actualizar contador de total de envíos
                            $('#total-envios').text(this.api().data().count());
                        }
                    });
                });
            </script>

            <!-- Estilos personalizados para DataTables -->
            <style>
                .dataTables_wrapper .dt-buttons {
                    margin-bottom: 15px;
                }
                
                .dataTables_wrapper .dt-button {
                    background: #3b82f6 !important;
                    color: white !important;
                    border: none !important;
                    padding: 8px 16px !important;
                    border-radius: 6px !important;
                    margin-right: 8px !important;
                    font-size: 14px !important;
                    font-weight: 500 !important;
                    transition: all 0.2s !important;
                }
                
                .dataTables_wrapper .dt-button:hover {
                    background: #2563eb !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                }
                
                .dataTables_wrapper .dataTables_filter input {
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    padding: 8px 12px !important;
                    margin-left: 8px !important;
                }
                
                .dataTables_wrapper .dataTables_filter input:focus {
                    outline: none !important;
                    border-color: #3b82f6 !important;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                }
                
                .dataTables_wrapper .dataTables_length select {
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    padding: 4px 8px !important;
                    margin: 0 8px !important;
                }
                
                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    padding: 8px 12px !important;
                    margin: 0 2px !important;
                    background: white !important;
                    color: #374151 !important;
                }
                
                .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                    background: #f3f4f6 !important;
                    border-color: #9ca3af !important;
                }
                
                .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                    background: #3b82f6 !important;
                    border-color: #3b82f6 !important;
                    color: white !important;
                }
                
                .dataTables_wrapper .dataTables_info {
                    color: #6b7280 !important;
                    font-size: 14px !important;
                }
                
                #tabla-envios {
                    border-collapse: collapse !important;
                    width: 100% !important;
                }
                
                #tabla-envios th {
                    background: #f9fafb !important;
                    padding: 12px 16px !important;
                    text-align: left !important;
                    font-weight: 600 !important;
                    color: #374151 !important;
                    border-bottom: 2px solid #e5e7eb !important;
                }
                
                #tabla-envios td {
                    padding: 12px 16px !important;
                    border-bottom: 1px solid #f3f4f6 !important;
                }
                
                #tabla-envios tbody tr:hover {
                    background: #f9fafb !important;
                }
            </style>

            </div>
        </div>
    </div>

    <!-- JavaScript para actualizar saldo de Twilio -->
    <script>
        function actualizarSaldoTwilio() {
            const saldoSection = document.querySelector('.bg-white.rounded-xl.shadow-lg.p-6.mb-8');
            const button = document.querySelector('button[onclick="actualizarSaldoTwilio()"]');
            
            // Mostrar estado de carga
            button.disabled = true;
            button.innerHTML = `
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Actualizando...
            `;
            
            fetch('{{ route("twilio.saldo") }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar la sección del saldo
                    location.reload();
                } else {
                    alert('Error al actualizar el saldo: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el saldo');
            })
            .finally(() => {
                // Restaurar botón
                button.disabled = false;
                button.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualizar
                `;
            });
        }

        // Función para copiar enlace de encuesta
        function copiarEnlaceEncuesta(envioId) {
            // Obtener URL encriptada del backend
            fetch(`/envios/${envioId}/url-encriptada`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const enlace = data.url;
                        
                        // Crear un elemento temporal para copiar
                        const textArea = document.createElement('textarea');
                        textArea.value = enlace;
                        document.body.appendChild(textArea);
                        textArea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textArea);
                        
                        // Mostrar notificación
                        const notification = document.createElement('div');
                        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center';
                        notification.innerHTML = `
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Enlace copiado al portapapeles
                        `;
                        document.body.appendChild(notification);
                        
                        // Remover notificación después de 3 segundos
                        setTimeout(() => {
                            document.body.removeChild(notification);
                        }, 3000);
                    } else {
                        // Mostrar error
                        alert('Error al generar el enlace: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al generar el enlace de la encuesta');
                });
        }
    </script>
</x-app-layout> 