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

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Cards de estadísticas mejoradas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-8 sm:mb-12">
                <!-- Card 1 - Total Envíos -->
                <div class="bg-blue-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-blue-100 text-xs sm:text-sm font-medium">Total Envíos</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ number_format($totalEnvios) }}</p>
                            <p class="text-blue-100 text-xs sm:text-sm mt-1 truncate">{{ $crecimientoMensual > 0 ? '+' : '' }}{{ $crecimientoMensual }}% este mes</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 2 - Encuestas Completadas -->
                <div class="bg-green-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-green-100 text-xs sm:text-sm font-medium">Encuestas Completadas</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ number_format($enviosCompletados) }}</p>
                            <p class="text-green-100 text-xs sm:text-sm mt-1 truncate">{{ $tasaRespuesta }}% tasa de respuesta</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3 5.983C3 4.888 3.895 4 5 4h14c1.105 0 2 .888 2 1.983v8.923a1.992 1.992 0 0 1-2 1.983h-6.6l-2.867 2.7c-.955.899-2.533.228-2.533-1.08v-1.62H5c-1.105 0-2-.888-2-1.983V5.983Zm5.706 3.809a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Zm2.585.002a1 1 0 1 1 .003 1.414 1 1 0 0 1-.003-1.414Zm5.415-.002a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 3 - NPS Score -->
                <div class="bg-yellow-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-yellow-100 text-xs sm:text-sm font-medium">NPS Score</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $npsPromedio['nps_score'] }}</p>
                            <p class="text-yellow-100 text-xs sm:text-sm mt-1 truncate">{{ $npsPromedio['total'] }} respuestas</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Card 4 - Calidad Promedio -->
                <div class="bg-purple-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-purple-100 text-xs sm:text-sm font-medium">Calidad Promedio</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $estadisticasCalidad['promedio_general'] }}/10</p>
                            <p class="text-purple-100 text-xs sm:text-sm mt-1 truncate">Calidad del producto</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico y tabla mejorados -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 mb-8 sm:mb-12">
                <!-- Gráfico del NPS -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Net Promoter Score (NPS)</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                <span class="text-xs sm:text-sm text-gray-600">Últimos 6 meses</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <!-- NPS Score Principal -->
                        <div class="text-center mb-4 sm:mb-6">
                            <div class="text-3xl sm:text-4xl font-bold text-green-600 mb-2">{{ $npsPromedio['nps_score'] }}</div>
                            <div class="text-xs sm:text-sm text-gray-600">NPS Score</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $npsPromedio['total'] }} respuestas</div>
                        </div>

                        <!-- Distribución de Respuestas -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4 sm:mb-6">
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl font-bold text-green-600">{{ $npsPromedio['promotores'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Promotores (9-10)</div>
                                <div class="text-xs text-green-500 font-medium">{{ $npsPromedio['porcentaje_promotores'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $npsPromedio['pasivos'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Pasivos (7-8)</div>
                                <div class="text-xs text-yellow-500 font-medium">{{ $npsPromedio['porcentaje_pasivos'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl font-bold text-red-600">{{ $npsPromedio['detractores'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Detractores (0-6)</div>
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
                        <div class="h-24 sm:h-32">
                            @if($datosNPS->count() > 0)
                            <div class="flex items-end justify-between h-full space-x-0.5 sm:space-x-1">
                                @foreach($datosNPS as $dato)
                                @php
                                $mes = Carbon\Carbon::createFromDate($dato->año, $dato->mes, 1)->format('M');
                                $npsValue = $dato->nps_promedio ?: 0;
                                $altura = $npsValue > 0 ? ($npsValue / 10) * 100 : 0;
                                @endphp
                                <div class="flex flex-col items-center flex-1 min-w-0">
                                    <div class="w-full {{ $npsValue >= 7 ? 'bg-green-500' : ($npsValue >= 5 ? 'bg-yellow-500' : 'bg-red-500') }} rounded-t" style="height: {{ $altura }}%"></div>
                                    <span class="text-xs text-gray-600 mt-1 truncate w-full text-center">{{ $mes }}</span>
                                    <span class="text-xs text-gray-500 truncate w-full text-center">{{ round($npsValue, 1) }}</span>
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
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Estados de Envíos</h3>
                            <a href="{{ route('envios.index') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Ver todos</a>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[500px]">
                            <thead class="bg-gray-50">
                                <tr>
                                  
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($enviosPorEstado->take(5) as $estado)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-3 sm:px-6 py-3 sm:py-4 text-xs sm:text-sm text-gray-500">
                                        @if($estado->estado === 'completado')
                                        <span class="text-green-600">✓ Respondido por el usuario</span>
                                        @elseif($estado->estado === 'enviado')
                                        <span class="text-blue-600">📤 Enviado por Whatsapp</span>
                                        @elseif($estado->estado === 'pendiente')
                                        <span class="text-yellow-600">⏳ Pendiente de envío</span>
                                        @elseif($estado->estado === 'cancelado')
                                        <span class="text-red-600">✗ Sin respuesta</span>
                                        @elseif($estado->estado === 'recordatorio_enviado')
                                        <span class="text-orange-600">⏳ Recordatorio enviado</span>
                                        @elseif($estado->estado === 'en_proceso')
                                        <span class="text-purple-600">⏳ Usuario respondiendo</span>
                                        @endif
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 sm:py-4">
                                        <div class="flex items-center">
                                            <div class="w-12 sm:w-16 bg-gray-200 rounded-full h-2">
                                                @php
                                                $porcentaje = $totalEnvios > 0 ? ($estado->total / $totalEnvios) * 100 : 0;
                                                @endphp
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                            </div>
                                            <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap">{{ round($porcentaje, 1) }}%</span>
                                        </div>
                                    </td>
                                    
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs sm:text-sm text-gray-500">
                                        No hay datos para mostrar
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Nueva sección: Análisis de Calidad del Producto -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-10 mb-8 sm:mb-12">
                <!-- Gráfico de aspectos de calidad -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Aspectos de Calidad</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                <span class="text-xs sm:text-sm text-gray-600">Promedios</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($estadisticasCalidad['aspectos'] as $aspecto)
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-500 rounded-full mr-2 sm:mr-3 flex-shrink-0"></div>
                                    <span class="text-xs sm:text-sm text-gray-700 truncate">{{ $aspecto['nombre'] }}</span>
                                </div>
                                <div class="flex items-center flex-shrink-0">
                                    <div class="w-12 sm:w-16 bg-gray-200 rounded-full h-2 mr-1 sm:mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($aspecto['promedio'] / 10) * 100 }}%"></div>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap">{{ $aspecto['promedio'] }}/10</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Mejor y peor aspecto -->
                        <div class="mt-4 sm:mt-6 pt-4 border-t border-gray-100">
                            <div class="grid grid-cols-2 gap-2 sm:gap-4">
                                <div class="text-center p-2 sm:p-3 bg-green-50 rounded-lg">
                                    <div class="text-xs sm:text-sm text-green-600 font-medium">Mejor Aspecto</div>
                                    <div class="text-sm sm:text-lg font-bold text-green-700 truncate">{{ $estadisticasCalidad['mejor_aspecto'] }}</div>
                                </div>
                                <div class="text-center p-2 sm:p-3 bg-red-50 rounded-lg">
                                    <div class="text-xs sm:text-sm text-red-600 font-medium">Necesita Mejora</div>
                                    <div class="text-sm sm:text-lg font-bold text-red-700 truncate">{{ $estadisticasCalidad['peor_aspecto'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top asesores comerciales -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 p-6 sm:p-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Top Asesores del Mes</h3>
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($topAsesoresMes as $asesor)
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-xs sm:text-sm text-gray-600 truncate flex-1 min-w-0">{{ $asesor->asesor_comercial ?: 'Sin asesor' }}</span>
                            <span class="text-xs sm:text-sm font-medium text-blue-600 whitespace-nowrap">{{ $asesor->total_envios }} envíos</span>
                        </div>
                        @empty
                        <p class="text-xs sm:text-sm text-gray-500">No hay datos de asesores</p>
                        @endforelse
                    </div>
                </div>

                <!-- Resumen de recomendaciones -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 p-6 sm:p-8">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Resumen de Recomendaciones</h3>
                    <div class="space-y-3 sm:space-y-4">
                        @php
                        $recomendacionesSi = App\Models\Envio::where('estado', 'completado')
                        ->where('respuesta_2', 'like', '%si%')
                        ->orWhere('respuesta_2', 'like', '%Si%')
                        ->count();
                        $recomendacionesNo = App\Models\Envio::where('estado', 'completado')
                        ->where('respuesta_2', 'like', '%no%')
                        ->orWhere('respuesta_2', 'like', '%No%')
                        ->count();
                        $totalRecomendaciones = $recomendacionesSi + $recomendacionesNo;
                        $porcentajeSi = $totalRecomendaciones > 0 ? round(($recomendacionesSi / $totalRecomendaciones) * 100, 1) : 0;
                        @endphp

                        <div class="text-center">
                            <div class="text-2xl sm:text-3xl font-bold text-green-600 mb-1 sm:mb-2">{{ $porcentajeSi }}%</div>
                            <div class="text-xs sm:text-sm text-gray-600">Recomendarían Konkret</div>
                        </div>

                        <div class="grid grid-cols-2 gap-2 sm:gap-4 text-center">
                            <div class="p-2 sm:p-3 bg-green-50 rounded-lg">
                                <div class="text-base sm:text-lg font-bold text-green-600">{{ $recomendacionesSi }}</div>
                                <div class="text-xs text-green-600">Sí</div>
                            </div>
                            <div class="p-2 sm:p-3 bg-red-50 rounded-lg">
                                <div class="text-base sm:text-lg font-bold text-red-600">{{ $recomendacionesNo }}</div>
                                <div class="text-xs text-red-600">No</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección adicional: Estadísticas detalladas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10">
                <!-- Envíos por estado -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Envíos por Estado</h3>
                            <a href="{{ route('envios.index') }}" class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Ver todos</a>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="space-y-3 sm:space-y-4">
                            @php
                            $estados = [
                            'completado' => ['nombre' => 'Respondido por el usuario', 'color' => 'green', 'icon' => '✓'],
                            'en_proceso' => ['nombre' => 'Usuario respondiendo', 'color' => 'purple', 'icon' => '⏳'],
                            'pendiente' => ['nombre' => 'Pendiente de envío', 'color' => 'yellow', 'icon' => '⏰'],
                            'cancelado' => ['nombre' => 'Sin respuesta', 'color' => 'red', 'icon' => '✗'],
                            'recordatorio_enviado' => ['nombre' => 'Recordatorio enviado', 'color' => 'orange', 'icon' => '⏳'],
                            'enviado' => ['nombre' => 'Enviado por Whatsapp', 'color' => 'blue', 'icon' => '📤']
                            ];
                            @endphp

                            @foreach($estados as $estado => $info)
                            @php
                            $total = $enviosPorEstado->where('estado', $estado)->first()->total ?? 0;
                            $porcentaje = $totalEnvios > 0 ? round(($total / $totalEnvios) * 100, 1) : 0;
                            @endphp
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-{{ $info['color'] }}-100 rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <span class="text-{{ $info['color'] }}-600 font-medium text-xs sm:text-sm">{{ $info['icon'] }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $info['nombre'] }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $total }} envíos</div>
                                    </div>
                                </div>
                                <div class="flex items-center flex-shrink-0">
                                    <div class="w-12 sm:w-16 bg-gray-200 rounded-full h-2 mr-1 sm:mr-2">
                                        <div class="bg-{{ $info['color'] }}-600 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-gray-900 whitespace-nowrap">{{ $porcentaje }}%</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Actividad reciente -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">Actividad Reciente </h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                <span class="text-xs sm:text-sm text-gray-600">7 días</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="space-y-3 sm:space-y-4">
                            @php
                            $enviosRecientes = App\Models\Envio::with('cliente')
                            ->where('fecha_envio', '>=', Carbon\Carbon::now()->subDays(7))
                            ->orderBy('fecha_envio', 'desc')
                            ->limit(5)
                            ->get();
                            @endphp

                            @forelse($enviosRecientes as $envio)
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center min-w-0 flex-1">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2 sm:mr-3 flex-shrink-0">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs sm:text-sm font-medium text-gray-900 truncate">{{ $envio->cliente->razon_social ?? 'Cliente' }}</div>
                                        <div class="text-xs sm:text-sm text-gray-500">{{ $envio->fecha_envio ? $envio->fecha_envio->format('d/m/Y H:i') : 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center flex-shrink-0">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $envio->estado === 'completado' ? 'bg-green-100 text-green-800' : 
                                               ($envio->estado === 'en_proceso' ? 'bg-purple-100 text-purple-800' : 
                                               ($envio->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')) }}">
                                        {{ ucfirst($envio->estado === 'completado' ? 'Respondido por el usuario' : ($envio->estado === 'en_proceso' ? 'Usuario respondiendo' : ($envio->estado === 'pendiente' ? 'Pendiente de envío' : 'Sin respuesta'))) }}
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-3 sm:py-4">
                                <p class="text-xs sm:text-sm text-gray-500">No hay actividad reciente</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>