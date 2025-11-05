<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Resultados') }}
            </h2>
            <a href="{{ route('resultados.index') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 sm:px-4 rounded text-sm sm:text-base">
                <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
                Volver a Gráficas
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Indicador de carga para móviles -->
            <div id="mobile-loading-indicator" class="fixed top-0 left-0 w-full h-1 bg-blue-600 z-50 transform -translate-y-full transition-transform duration-300">
                <div class="h-full bg-blue-400 animate-pulse"></div>
            </div>
            <!-- Estadísticas por asesor -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg sm:rounded-lg mb-8 sm:mb-12">
                <div class="p-6 sm:p-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4 sm:mb-6">Estadísticas por Asesor Comercial</h3>
                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Asesor</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Envíos</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completados</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cancelados</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pendientes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($estadisticasAsesores as $asesor)
                                <tr>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm font-medium text-gray-900">
                                        {{ $asesor->asesor_comercial }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                        {{ $asesor->total_envios }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                        {{ $asesor->completados }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                        {{ $asesor->cancelados }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                        {{ $asesor->pendientes }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Filtro de envios -->
            <div class="bg-white shadow-sm rounded-lg border border-gray-100">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Envíos</h3>

                        <div class="flex items-center gap-2">
                            <label for="filtroEstado" class="text-sm text-gray-600 font-medium">Filtrar por estado:</label>
                            <select id="filtroEstado"
                                class="border-gray-300 rounded-md text-sm py-1 px-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todos</option>
                                <option value="Completado">Completado</option>
                                <option value="Pendiente">Pendiente</option>
                                <option value="Cancelado">Cancelado</option>
                            </select>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="tabla-envios" class="min-w-full text-sm text-gray-700">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="py-2 px-3 text-left font-semibold text-gray-600">Cliente</th>
                                    <th class="py-2 px-3 text-left font-semibold text-gray-600">Asesor</th>
                                    <th class="py-2 px-3 text-left font-semibold text-gray-600">Estado</th>
                                    <th class="py-2 px-3 text-left font-semibold text-gray-600">Fecha Envío</th>
                                    <th class="py-2 px-3 text-left font-semibold text-gray-600">Fecha Respuesta</th>
                                    <th class="py-2 px-3 text-center font-semibold text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($enviosRecientes as $envio)
                                @php
                                $estadoAgrupado = match($envio->estado) {
                                'completado' => 'Completado',
                                'enviado', 'en_proceso', 'recordatorio_enviado' => 'Pendiente',
                                'cancelado', 'sin_respuesta' => 'Cancelado',
                                default => 'Otro'
                                };
                                @endphp
                                <tr class="bg-white hover:bg-gray-50 transition-colors">
                                    <td class="py-2 px-3 font-medium text-gray-800 bg-white">{{ $envio->cliente->razon_social ?? '-' }}</td>
                                    <td class="py-2 px-3 bg-white">{{ $envio->cliente->asesor_comercial ?? '-' }}</td>
                                    <td class="py-2 px-3 bg-white">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $estadoAgrupado === 'Completado' ? 'bg-green-100 text-green-800' : ($estadoAgrupado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : ($estadoAgrupado === 'Cancelado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $estadoAgrupado }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 bg-white">{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="py-2 px-3 bg-white">{{ $envio->fecha_respuesta ? $envio->fecha_respuesta->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="py-2 px-3 text-center bg-white">
                                        <a href="{{ route('envios.show', $envio->idenvio) }}" class="text-blue-600 hover:text-blue-800 font-semibold transition">Ver</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.tailwindcss.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.tailwindcss.css">

    <style>
        #tabla-envios,
        #tabla-envios thead,
        #tabla-envios thead th,
        #tabla-envios tbody,
        #tabla-envios tbody tr,
        #tabla-envios tbody td {
            background-color: white !important;
        }
        
        #tabla-envios tbody tr:hover {
            background-color: #f9fafb !important;
        }
        
        #tabla-envios_wrapper .dataTables_wrapper {
            background-color: white !important;
        }
        
        #tabla-envios_wrapper .dataTables_length,
        #tabla-envios_wrapper .dataTables_filter,
        #tabla-envios_wrapper .dataTables_info,
        #tabla-envios_wrapper .dataTables_paginate {
            margin-bottom: 0.5rem !important;
            padding: 0.5rem 0 !important;
        }
        
        #tabla-envios_wrapper .dataTables_length label,
        #tabla-envios_wrapper .dataTables_filter label {
            margin-bottom: 0 !important;
        }
        
        /* Forzar campo de búsqueda a modo claro - estilos más específicos */
        #tabla-envios_wrapper .dataTables_filter input[type="search"],
        #tabla-envios_wrapper .dataTables_filter input,
        div.dataTables_wrapper div.dataTables_filter input {
            background-color: white !important;
            background: white !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 0.75rem !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            color-scheme: light !important;
        }
        
        #tabla-envios_wrapper .dataTables_filter input[type="search"]:focus,
        #tabla-envios_wrapper .dataTables_filter input:focus,
        div.dataTables_wrapper div.dataTables_filter input:focus {
            background-color: white !important;
            background: white !important;
            color: #1f2937 !important;
            border-color: #3b82f6 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            color-scheme: light !important;
        }
        
        #tabla-envios_wrapper .dataTables_filter input[type="search"]::placeholder,
        #tabla-envios_wrapper .dataTables_filter input::placeholder,
        div.dataTables_wrapper div.dataTables_filter input::placeholder {
            color: #9ca3af !important;
            opacity: 1 !important;
        }
        
        /* Forzar modo claro en modo oscuro del sistema */
        @media (prefers-color-scheme: dark) {
            #tabla-envios_wrapper .dataTables_filter input[type="search"],
            #tabla-envios_wrapper .dataTables_filter input,
            div.dataTables_wrapper div.dataTables_filter input {
                background-color: white !important;
                background: white !important;
                color: #1f2937 !important;
                border: 1px solid #d1d5db !important;
                color-scheme: light !important;
            }
            
            #tabla-envios_wrapper .dataTables_filter input[type="search"]:focus,
            #tabla-envios_wrapper .dataTables_filter input:focus,
            div.dataTables_wrapper div.dataTables_filter input:focus {
                background-color: white !important;
                background: white !important;
                color: #1f2937 !important;
                border-color: #3b82f6 !important;
                color-scheme: light !important;
            }
            
            #tabla-envios_wrapper .dataTables_filter label {
                color: #1f2937 !important;
            }
        }
        
        /* Forzar select a modo claro */
        #tabla-envios_wrapper .dataTables_length select,
        #tabla-envios_wrapper select,
        div.dataTables_wrapper div.dataTables_length select {
            background-color: white !important;
            background: white !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        
        /* Forzar botones de paginación a modo claro - estilos más específicos */
        #tabla-envios_wrapper .dataTables_paginate .paginate_button,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button,
        #tabla-envios_wrapper .dataTables_paginate a.paginate_button,
        div.dataTables_wrapper div.dataTables_paginate a.paginate_button {
            background-color: white !important;
            background: white !important;
            color: #1f2937 !important;
            border: 1px solid #d1d5db !important;
            padding: 0.5rem 0.75rem !important;
            margin: 0 0.25rem !important;
            border-radius: 0.375rem !important;
        }
        
        #tabla-envios_wrapper .dataTables_paginate .paginate_button.current,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
        #tabla-envios_wrapper .dataTables_paginate a.paginate_button.current,
        div.dataTables_wrapper div.dataTables_paginate a.paginate_button.current {
            background-color: #3b82f6 !important;
            background: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }
        
        #tabla-envios_wrapper .dataTables_paginate .paginate_button:hover,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover,
        #tabla-envios_wrapper .dataTables_paginate a.paginate_button:hover,
        div.dataTables_wrapper div.dataTables_paginate a.paginate_button:hover {
            background-color: #f3f4f6 !important;
            background: #f3f4f6 !important;
            color: #1f2937 !important;
        }
        
        #tabla-envios_wrapper .dataTables_paginate .paginate_button.disabled,
        div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled {
            background-color: #f9fafb !important;
            background: #f9fafb !important;
            color: #9ca3af !important;
        }
        
        /* Forzar textos a modo claro */
        #tabla-envios_wrapper .dataTables_info,
        #tabla-envios_wrapper .dataTables_length label,
        #tabla-envios_wrapper .dataTables_filter label,
        div.dataTables_wrapper div.dataTables_info,
        div.dataTables_wrapper div.dataTables_length label,
        div.dataTables_wrapper div.dataTables_filter label {
            color: #1f2937 !important;
        }
        
        /* Forzar color-scheme para evitar modo oscuro del sistema */
        #tabla-envios_wrapper,
        #tabla-envios_wrapper *,
        div.dataTables_wrapper,
        div.dataTables_wrapper * {
            color-scheme: light !important;
        }
        
        /* Estilos adicionales para forzar modo claro en todos los elementos */
        #tabla-envios_wrapper input,
        #tabla-envios_wrapper button,
        #tabla-envios_wrapper select,
        div.dataTables_wrapper input,
        div.dataTables_wrapper button,
        div.dataTables_wrapper select {
            color-scheme: light !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = new DataTable('#tabla-envios', {
                pageLength: 10,
                order: [
                    [3, 'desc']
                ],
                language: {
                    search: "Buscar:",
                    lengthMenu: "Mostrar _MENU_ registros por página",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                    infoEmpty: "Mostrando 0 a 0 de 0 registros",
                    infoFiltered: "(filtrado de _MAX_ registros totales)",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
                    },
                    zeroRecords: "No se encontraron registros",
                    emptyTable: "No hay datos disponibles en la tabla"
                }
            });

            const filtro = document.getElementById('filtroEstado');
            filtro.addEventListener('change', (e) => {
                const estado = e.target.value;
                table.columns(2).search(estado || '').draw();
            });
        });
    </script>
</x-app-layout>