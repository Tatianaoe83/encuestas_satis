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
                                        {{ $asesor->pendientes }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-xs sm:text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $asesor->tasa_respuesta >= 80 ? 'bg-green-100 text-green-800' : 
                                               ($asesor->tasa_respuesta >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ $asesor->tasa_respuesta }}%
                                        </span>
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
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">📦 Envíos</h3>

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
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Cliente</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Asesor</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Estado</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Fecha Envío</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-600">Fecha Respuesta</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($enviosRecientes as $envio)
                                @php
                                $estadoAgrupado = match($envio->estado) {
                                'completado' => 'Completado',
                                'enviado', 'en_proceso', 'recordatorio_enviado' => 'Pendiente',
                                'cancelado', 'sin_respuesta' => 'Cancelado',
                                default => 'Otro'
                                };
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 font-medium text-gray-800">{{ $envio->cliente->razon_social ?? '-' }}</td>
                                    <td class="py-3 px-4">{{ $envio->cliente->asesor_comercial ?? '-' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                    {{ $estadoAgrupado === 'Completado' ? 'bg-green-100 text-green-800' :
                                       ($estadoAgrupado === 'Pendiente' ? 'bg-yellow-100 text-yellow-800' :
                                       ($estadoAgrupado === 'Cancelado' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ $estadoAgrupado }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="py-3 px-4">{{ $envio->fecha_respuesta ? $envio->fecha_respuesta->format('d/m/Y H:i') : '-' }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('envios.show', $envio->idenvio) }}"
                                            class="text-blue-600 hover:text-blue-800 font-semibold transition">
                                            Ver
                                        </a>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const table = new DataTable('#tabla-envios', {
                pageLength: 10,
                order: [
                    [3, 'desc']
                ],
            });

            const filtro = document.getElementById('filtroEstado');
            filtro.addEventListener('change', (e) => {
                const estado = e.target.value;
                table.columns(2).search(estado || '').draw();
            });
        });
    </script>
</x-app-layout>