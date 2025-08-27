<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-gray-600 mt-1">Bienvenido al panel de control de encuestas</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards de estadísticas mejoradas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 - Total Envíos -->
                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Envíos</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($totalEnvios) }}</p>
                            <p class="text-blue-100 text-sm mt-1">{{ $crecimientoMensual > 0 ? '+' : '' }}{{ $crecimientoMensual }}% este mes</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2 - Encuestas Enviadas -->
                <div class="bg-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Encuestas Enviadas</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($enviosMesActual) }}</p>
                            <p class="text-green-100 text-sm mt-1">Este mes</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - Respuestas -->
                <div class="bg-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Respuestas</p>
                            <p class="text-3xl font-bold mt-2">{{ number_format($enviosCompletados) }}</p>
                            <p class="text-yellow-100 text-sm mt-1">{{ $tasaRespuesta }}% tasa de respuesta</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3.559 4.544c.355-.35.834-.544 1.33-.544H19.11c.496 0 .975.194 1.33.544.356.35.559.829.559 1.331v9.25c0 .502-.203.981-.559 1.331-.355.35-.834.544-1.33.544H15.5l-2.7 3.6a1 1 0 0 1-1.6 0L8.5 17H4.889c-.496 0-.975-.194-1.33-.544A1.868 1.868 0 0 1 3 15.125v-9.25c0 .502.203.981.559-1.331ZM7.556 7.5a1 1 0 1 0 0 2h8a1 1 0 0 0 0-2h-8Zm0 3.5a1 1 0 1 0 0 2H12a1 1 0 1 0 0-2H7.556Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4 - Tiempo Promedio -->
                <div class="bg-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Tiempo Promedio</p>
                            <p class="text-3xl font-bold mt-2">{{ round($promedioRespuesta, 1) }}h</p>
                            <p class="text-purple-100 text-sm mt-1">Respuesta promedio</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                              </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico y tabla mejorados -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfico del NPS -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Net Promoter Score (NPS)</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Últimos 6 meses</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <!-- NPS Score Principal -->
                        <div class="text-center mb-6">
                            <div class="text-4xl font-bold text-green-600 mb-2">{{ $npsPromedio['nps_score'] }}</div>
                            <div class="text-sm text-gray-600">NPS Score</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $npsPromedio['total'] }} respuestas</div>
                        </div>

                        <!-- Distribución de Respuestas -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $npsPromedio['promotores'] }}</div>
                                <div class="text-xs text-gray-600">Promotores (9-10)</div>
                                <div class="text-xs text-green-500 font-medium">{{ $npsPromedio['porcentaje_promotores'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $npsPromedio['pasivos'] }}</div>
                                <div class="text-xs text-gray-600">Pasivos (7-8)</div>
                                <div class="text-xs text-yellow-500 font-medium">{{ $npsPromedio['porcentaje_pasivos'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $npsPromedio['detractores'] }}</div>
                                <div class="text-xs text-gray-600">Detractores (0-6)</div>
                                <div class="text-xs text-red-500 font-medium">{{ $npsPromedio['porcentaje_detractores'] }}%</div>
                            </div>
                        </div>

                        <!-- Fórmula del NPS -->
                        <div class="text-center mb-4 p-3 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-600 mb-1">Fórmula NPS:</div>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $npsPromedio['porcentaje_promotores'] }}% - {{ $npsPromedio['porcentaje_detractores'] }}% = {{ $npsPromedio['nps_score'] }}
                            </div>
                        </div>

                        <!-- Gráfico de Tendencia Mensual -->
                        <div class="h-32">
                            @if($datosNPS->count() > 0)
                                <div class="flex items-end justify-between h-full space-x-1">
                                    @foreach($datosNPS as $dato)
                                        @php
                                            $mes = Carbon\Carbon::createFromDate($dato->año, $dato->mes, 1)->format('M');
                                            $npsValue = $dato->nps_promedio ?: 0;
                                            $altura = $npsValue > 0 ? ($npsValue / 10) * 100 : 0;
                                        @endphp
                                        <div class="flex flex-col items-center">
                                            <div class="w-6 {{ $npsValue >= 7 ? 'bg-green-500' : ($npsValue >= 5 ? 'bg-yellow-500' : 'bg-red-500') }} rounded-t" style="height: {{ $altura }}%"></div>
                                            <span class="text-xs text-gray-600 mt-1">{{ $mes }}</span>
                                            <span class="text-xs text-gray-500">{{ round($npsValue, 1) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="flex items-center justify-center h-full">
                                    <p class="text-sm text-gray-500">No hay datos de NPS</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tabla de estados de envíos -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Estados de Envíos</h3>
                            <a href="{{ route('envios.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver todos</a>
                        </div>
                    </div>
                    <div class="overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Porcentaje</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($enviosPorEstado->take(5) as $estado)
                                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 {{ $estado->estado === 'completado' ? 'bg-green-100' : ($estado->estado === 'enviado' ? 'bg-blue-100' : 'bg-yellow-100') }} rounded-full flex items-center justify-center mr-3">
                                                    <span class="{{ $estado->estado === 'completado' ? 'text-green-600' : ($estado->estado === 'enviado' ? 'text-blue-600' : 'text-yellow-600') }} font-medium text-sm">{{ strtoupper(substr($estado->estado, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ ucfirst($estado->estado) }}</div>
                                                    <div class="text-sm text-gray-500">{{ $estado->total }} envíos</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    @php
                                                        $porcentaje = $totalEnvios > 0 ? ($estado->total / $totalEnvios) * 100 : 0;
                                                    @endphp
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                                </div>
                                                <span class="ml-2 text-sm font-medium text-gray-900">{{ round($porcentaje, 1) }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($estado->estado === 'completado')
                                                <span class="text-green-600">✓ Completado</span>
                                            @elseif($estado->estado === 'enviado')
                                                <span class="text-blue-600">📤 Enviado</span>
                                            @else
                                                <span class="text-yellow-600">⏳ Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                            No hay datos para mostrar
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

         

                <!-- Top asesores comerciales -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Asesores del Mes</h3>
                    <div class="space-y-3">
                        @forelse($topAsesoresMes as $asesor)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ $asesor->asesor_comercial ?: 'Sin asesor' }}</span>
                                <span class="text-sm font-medium text-blue-600">{{ $asesor->total_envios }} envíos</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No hay datos de asesores</p>
                        @endforelse
                    </div>
                </div>

      
              
            </div>
        </div>
    </div>
</x-app-layout>
