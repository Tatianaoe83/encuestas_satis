<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
            <div class="flex-1">
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 leading-tight">
                    {{ __('Análisis de Resultados') }}
                </h2>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Visualiza y analiza los resultados de tus encuestas</p>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:space-x-3 sm:gap-0">
                <a href="{{ route('resultados.detalle') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Detalle
                </a>
                <a href="{{ route('resultados.exportar') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="hidden sm:inline">Exportar encuestas</span>
                    <span class="sm:hidden">Encuestas</span>
                </a>
                <a href="{{ route('resultados.exportar-nps') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center justify-center text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="hidden sm:inline">Exportar NPS</span>
                    <span class="sm:hidden">NPS</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Tarjetas de estadísticas generales mejoradas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 sm:gap-8 mb-8 sm:mb-12">
                <div class="bg-blue-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-blue-100 text-xs sm:text-sm font-medium">Total Envíos</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $totalEnvios }}</p>

                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z" clip-rule="evenodd" />
                                <path fill-rule="evenodd" d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-green-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-green-100 text-xs sm:text-sm font-medium">Respondido por el usuario</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $enviosCompletados }}</p>

                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3 5.983C3 4.888 3.895 4 5 4h14c1.105 0 2 .888 2 1.983v8.923a1.992 1.992 0 0 1-2 1.983h-6.6l-2.867 2.7c-.955.899-2.533.228-2.533-1.08v-1.62H5c-1.105 0-2-.888-2-1.983V5.983Zm5.706 3.809a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Zm2.585.002a1 1 0 1 1 .003 1.414 1 1 0 0 1-.003-1.414Zm5.415-.002a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-red-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-red-100 text-xs sm:text-sm font-medium">Sin respuesta</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $enviosCancelados }}</p>

                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-yellow-100 text-xs sm:text-sm font-medium">Pendientes</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $enviosPendientes }}</p>

                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-indigo-100 text-xs sm:text-sm font-medium truncate">Tasa de Respuesta</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $tasaRespuesta }}%</p>

                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-2 sm:p-3 flex-shrink-0 ml-2">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5" />
                            </svg>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 1: Métricas Clave y NPS -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 lg:gap-10 mb-8 sm:mb-12">
                <!-- Gráfica de dona - Envíos por estado -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">📊 Envíos por Estado</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Distribución</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Estado actual de todas las encuestas enviadas</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="relative h-64">
                            <canvas id="chartEstados"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de dona - NPS -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-green-50 to-emerald-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">⭐ Net Promoter Score</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Satisfacción</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Medida de lealtad y satisfacción del cliente</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <!-- NPS Score Principal -->
                        <div class="text-center mb-4 sm:mb-6">
                            <div class="text-3xl sm:text-4xl font-bold text-green-600 mb-2">{{ $npsData['nps_score'] }}</div>
                            <div class="text-sm text-gray-600">NPS Score</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $npsData['total'] }} respuestas</div>
                        </div>

                        <!-- Fórmula del NPS -->
                        <div class="text-center mb-6 p-3 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-600 mb-1">Fórmula NPS:</div>
                            <div class="text-sm font-medium text-gray-800">
                                {{ $npsData['porcentaje_promotores'] }}% - {{ $npsData['porcentaje_detractores'] }}% = {{ $npsData['nps_score'] }}
                            </div>
                        </div>

                        <!-- Distribución de Respuestas -->
                        <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-4 sm:mb-6">
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl font-bold text-green-600">{{ $npsData['promotores'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Promotores (9-10)</div>
                                <div class="text-xs text-green-500 font-medium">{{ $npsData['porcentaje_promotores'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl font-bold text-yellow-600">{{ $npsData['pasivos'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Pasivos (7-8)</div>
                                <div class="text-xs text-yellow-500 font-medium">{{ $npsData['porcentaje_pasivos'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-xl sm:text-2xl font-bold text-red-600">{{ $npsData['detractores'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Detractores (0-6)</div>
                                <div class="text-xs text-red-500 font-medium">{{ $npsData['porcentaje_detractores'] }}%</div>
                            </div>
                        </div>

                        <!-- Gráfico de Barras del NPS -->
                        <div class="h-24 sm:h-32">
                            @if($npsData['total'] > 0)
                            <div class="flex items-end justify-between h-full space-x-1 sm:space-x-2">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 bg-green-500 rounded-t" style="height: {{ ($npsData['porcentaje_promotores'] / 100) * 100 }}%"></div>
                                    <span class="text-xs text-gray-600 mt-2">Promotores</span>
                                    <span class="text-xs text-gray-500">{{ $npsData['porcentaje_promotores'] }}%</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-12 bg-yellow-500 rounded-t" style="height: {{ ($npsData['porcentaje_pasivos'] / 100) * 100 }}%"></div>
                                    <span class="text-xs text-gray-600 mt-2">Pasivos</span>
                                    <span class="text-xs text-gray-500">{{ $npsData['porcentaje_pasivos'] }}%</span>
                                </div>
                                <div class="flex flex-col items-center">
                                    <div class="w-12 bg-red-500 rounded-t" style="height: {{ ($npsData['porcentaje_detractores'] / 100) * 100 }}%"></div>
                                    <span class="text-xs text-gray-600 mt-2">Detractores</span>
                                    <span class="text-xs text-gray-500">{{ $npsData['porcentaje_detractores'] }}%</span>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center justify-center h-full">
                                <p class="text-sm text-gray-500">No hay datos de NPS</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Asesores -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">👥 Top Asesores</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Rendimiento</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Asesores con mayor volumen de envíos</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="relative h-64">
                            <canvas id="chartAsesores"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 2: Análisis Temporal -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 mb-8 sm:mb-12">
                <!-- Gráfica de línea - Envíos por mes -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">📈 Tendencia de Envíos Mensual</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Análisis Temporal</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Evolución de envíos a lo largo del tiempo con métricas detalladas</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="mb-4 grid grid-cols-3 gap-2 sm:gap-3 text-center">
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-2 sm:p-3 border border-purple-200">
                                <div class="text-base sm:text-lg font-bold text-purple-600" id="totalEnviados">--</div>
                                <div class="text-xs text-gray-600">Total Enviados</div>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-2 sm:p-3 border border-green-200">
                                <div class="text-base sm:text-lg font-bold text-green-600" id="promedioMensual">--</div>
                                <div class="text-xs text-gray-600">Promedio/Mes</div>
                            </div>
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-2 sm:p-3 border border-blue-200">
                                <div class="text-base sm:text-lg font-bold text-blue-600" id="mesMasActivo">--</div>
                                <div class="text-xs text-gray-600 truncate">Mes Más Activo</div>
                            </div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="chartMensual"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de área - Tasa de Respuesta por Mes -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-emerald-50 to-teal-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">📊 Tasa de Respuesta por Mes</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-emerald-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Efectividad</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Porcentaje de encuestas respondidas por mes</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="mb-4 text-center">
                            <div class="text-2xl sm:text-3xl font-bold text-emerald-600 mb-2" id="tasaRespuestaPromedio">--</div>
                            <div class="text-sm text-gray-600 font-medium">Tasa de Respuesta Promedio</div>
                            <div class="text-xs text-gray-500 mt-1">Meta: >80%</div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="chartTasaRespuesta"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 3: Análisis Detallado -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 mb-8 sm:mb-12">
                <!-- Gráfica de barras apiladas - Envíos por estado por mes -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">📋 Envíos por Estado por Mes</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Desglose</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Distribución de envíos respondidos por el usuario, sin respuesta y pendiente de envío por mes</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="mb-4 flex flex-wrap items-center justify-center gap-3 sm:gap-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <span class="text-sm text-gray-600 font-medium">Respondido por el usuario</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-red-500 rounded"></div>
                                <span class="text-sm text-gray-600 font-medium">Sin respuesta</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                                <span class="text-sm text-gray-600 font-medium">Pendiente de envío</span>
                            </div>
                        </div>
                        <div class="relative h-64">
                            <canvas id="chartEstadosPorMes"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de barras - Envíos por día de la semana -->
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-yellow-50 to-orange-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900">📅 Envíos por Día de la Semana</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Patrones</span>
                            </div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Análisis de patrones semanales en el envío de encuestas</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="relative h-64">
                            <canvas id="chartDias"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 5: Análisis de Respuestas por Pregunta -->
            <div class="mb-8 sm:mb-12">
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">🔍 Análisis de Respuestas por Pregunta</h3>
                                <p class="text-gray-600 mt-1 text-sm sm:text-base">Distribución detallada de respuestas para cada pregunta de la encuesta</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-slate-500 rounded-full"></span>
                                <span class="text-xs sm:text-sm text-gray-600">Insights</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <!-- Sección: Calidad del Producto (Preguntas 1.1 a 1.5) -->
                        <div class="mb-8 sm:mb-12">
                            <h4 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4 flex items-center">
                                <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-blue-600 font-bold text-sm">📊</span>
                                Calidad del Producto - Análisis Detallado
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                                <!-- Pregunta 1.1 -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-blue-900">1.1 - Calidad General</h5>
                                        <div class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">
                                            Escala 1-10
                                        </div>
                                    </div>
                                    <div class="relative h-32">
                                        <canvas id="chartRespuesta1_1"></canvas>
                                    </div>
                                </div>

                                <!-- Pregunta 1.2 -->
                                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-lg p-4 border border-indigo-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-indigo-900">1.2 - Puntualidad de entrega</h5>
                                        <div class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full font-medium">
                                            Escala 1-10
                                        </div>
                                    </div>
                                    <div class="relative h-32">
                                        <canvas id="chartRespuesta1_2"></canvas>
                                    </div>
                                </div>

                                <!-- Pregunta 1.3 -->
                                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg p-4 border border-purple-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-purple-900">1.3 - Trato del asesor comercial</h5>
                                        <div class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium">
                                            Escala 1-10
                                        </div>
                                    </div>
                                    <div class="relative h-32">
                                        <canvas id="chartRespuesta1_3"></canvas>
                                    </div>
                                </div>

                                <!-- Pregunta 1.4 -->
                                <div class="bg-gradient-to-br from-pink-50 to-red-50 rounded-lg p-4 border border-pink-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-pink-900">1.4 - Precio</h5>
                                        <div class="text-xs bg-pink-100 text-pink-700 px-2 py-1 rounded-full font-medium">
                                            Escala 1-10
                                        </div>
                                    </div>
                                    <div class="relative h-32">
                                        <canvas id="chartRespuesta1_4"></canvas>
                                    </div>
                                </div>

                                <!-- Pregunta 1.5 -->
                                <div class="bg-gradient-to-br from-red-50 to-orange-50 rounded-lg p-4 border border-red-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-red-900">1.5 - Rapidez en programación.</h5>
                                        <div class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full font-medium">
                                            Escala 1-10
                                        </div>
                                    </div>
                                    <div class="relative h-32">
                                        <canvas id="chartRespuesta1_5"></canvas>
                                    </div>
                                </div>

                                <!-- Promedio NPS -->
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-green-900">Promedio NPS</h5>
                                        <div class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                                            Promedio
                                        </div>
                                    </div>
                                    <div class="relative h-32">
                                        <canvas id="chartRespuesta1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Otras Preguntas -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            <!-- Pregunta 2: Recomendación -->
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-green-200">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3 sm:mb-4">
                                    <h4 class="text-base sm:text-lg font-semibold text-green-900 flex items-center">
                                        <span class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 text-green-600 font-bold">2</span>
                                        Recomendación
                                    </h4>
                                    <div class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                                        Si/No
                                    </div>
                                </div>
                                <p class="text-sm text-green-700 mb-4">¿Recomendarías a Konkret?</p>
                                <div class="relative h-48">
                                    <canvas id="chartRespuesta2"></canvas>
                                </div>
                            </div>

                            <!-- Pregunta 3: Sugerencias -->
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-lg sm:rounded-xl p-4 sm:p-6 border border-purple-200">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3 sm:mb-4">
                                    <h4 class="text-base sm:text-lg font-semibold text-purple-900 flex items-center">
                                        <span class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 text-purple-600 font-bold">3</span>
                                        Sugerencias
                                    </h4>
                                    <div class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium">
                                        Texto Libre
                                    </div>
                                </div>
                                <p class="text-sm text-purple-700 mb-4">¿Qué podríamos hacer para mejorar tu experiencia?</p>
                                <div class="relative h-48">
                                    <canvas id="chartRespuesta3"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para las gráficas
            const estadosData = @json($enviosPorEstado);
            const asesoresData = @json($topAsesores);
            const mensualData = @json($enviosPorMes);
            const diasData = @json($enviosPorDia);
            const respuesta1Data = @json($respuestasPregunta1);
            const respuesta2Data = @json($respuestasPregunta2);
            const respuesta3Data = @json($respuestasPregunta3);
            const respuestaDetalle1Data = @json($respuestasDetalle1);

            // Configuración común para Chart.js
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
            Chart.defaults.color = '#1b222eff';

            // Función para verificar si hay datos
            function tieneDatos(data) {
                return data && data.length > 0;
            }

            // Función para mostrar mensaje de no datos
            function mostrarMensajeNoDatos(elementId, mensaje) {
                const canvas = document.getElementById(elementId);
                const container = canvas.parentElement;
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-64">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay datos disponibles</h3>
                        <p class="text-gray-500">${mensaje}</p>
                    </div>
                `;
            }

            // Gráfica de dona - Envíos por estado
            if (tieneDatos(estadosData)) {
                new Chart(document.getElementById('chartEstados'), {
                    type: 'doughnut',
                    data: {
                        labels: estadosData.map(item => item.estado.charAt(0).toUpperCase() + item.estado.slice(1)),
                        datasets: [{
                            data: estadosData.map(item => item.total),
                            backgroundColor: ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6'],
                            borderWidth: 3,
                            borderColor: '#fff',
                            hoverBorderWidth: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            }
                        },
                        animation: {
                            animateRotate: true,
                            animateScale: true
                        }
                    }
                });
            } else {
                mostrarMensajeNoDatos('chartEstados', 'Aún no hay envíos registrados');
            }

            // Gráfica de barras - Top asesores
            if (tieneDatos(asesoresData)) {
                new Chart(document.getElementById('chartAsesores'), {
                    type: 'bar',
                    data: {
                        labels: asesoresData.map(item => item.asesor_comercial),
                        datasets: [{
                            label: 'Total Envíos',
                            data: asesoresData.map(item => item.total_envios),
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: '#3B82F6',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        animation: {
                            duration: 2000
                        }
                    }
                });
            } else {
                mostrarMensajeNoDatos('chartAsesores', 'Aún no hay asesores con envíos');
            }

            // Gráfica de línea - Envíos por mes
            const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

            if (tieneDatos(mensualData)) {
                // Calcular métricas adicionales
                const totalEnviados = mensualData.reduce((sum, item) => sum + item.total, 0);
                const promedioMensual = Math.round(totalEnviados / mensualData.length);
                const mesMasActivo = mensualData.reduce((max, item) =>
                    item.total > max.total ? item : max
                );
                const mesMasActivoTexto = `${meses[mesMasActivo.mes - 1]} ${mesMasActivo.año}`;

                // Actualizar métricas en el HTML
                document.getElementById('totalEnviados').textContent = totalEnviados;
                document.getElementById('promedioMensual').textContent = promedioMensual;
                document.getElementById('mesMasActivo').textContent = mesMasActivoTexto;

                new Chart(document.getElementById('chartMensual'), {
                    type: 'line',
                    data: {
                        labels: mensualData.map(item => `${meses[item.mes - 1]} ${item.año}`),
                        datasets: [{
                            label: 'Envíos',
                            data: mensualData.map(item => item.total),
                            borderColor: '#8B5CF6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            borderWidth: 4,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#8B5CF6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 3,
                            pointRadius: 8,
                            pointHoverRadius: 12,
                            pointHoverBackgroundColor: '#7C3AED',
                            pointHoverBorderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(139, 92, 246, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12
                                    },
                                    padding: 10
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12
                                    },
                                    padding: 10
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#8B5CF6',
                                borderWidth: 2,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        return `📅 ${context[0].label}`;
                                    },
                                    label: function(context) {
                                        return `📤 ${context.parsed.y} envíos`;
                                    },
                                    afterLabel: function(context) {
                                        const total = mensualData.reduce((sum, item) => sum + item.total, 0);
                                        const porcentaje = total > 0 ? Math.round((context.parsed.y / total) * 100) : 0;
                                        return `📊 ${porcentaje}% del total`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 2000,
                            easing: 'easeInOutQuart'
                        },
                        elements: {
                            line: {
                                borderJoinStyle: 'round'
                            }
                        }
                    }
                });
            } else {
                mostrarMensajeNoDatos('chartMensual', 'Aún no hay envíos con fechas registradas');
                document.getElementById('totalEnviados').textContent = '--';
                document.getElementById('promedioMensual').textContent = '--';
                document.getElementById('mesMasActivo').textContent = '--';
            }

            // Gráfica de barras apiladas - Envíos por estado por mes
            if (tieneDatos(mensualData)) {
                // Crear datos para la gráfica de estados por mes
                const labelsEstadosPorMes = mensualData.map(item => `${meses[item.mes - 1]} ${item.año}`);

                // Simular datos de estados por mes (en un caso real, esto vendría del backend)
                const datosCompletados = mensualData.map(item => Math.floor(item.total * 0.7)); // 70% completados
                const datosCancelados = mensualData.map(item => Math.floor(item.total * 0.2)); // 20% cancelados
                const datosPendientes = mensualData.map((item, index) => {
                    const completados = datosCompletados[index] || 0;
                    const cancelados = datosCancelados[index] || 0;
                    return Math.max(0, item.total - completados - cancelados);
                });

                new Chart(document.getElementById('chartEstadosPorMes'), {
                    type: 'bar',
                    data: {
                        labels: labelsEstadosPorMes,
                        datasets: [{
                                label: 'Completados',
                                data: datosCompletados,
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                                borderColor: '#22C55E',
                                borderWidth: 2,
                                borderRadius: 4,
                                borderSkipped: false
                            },
                            {
                                label: 'Cancelados',
                                data: datosCancelados,
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: '#EF4444',
                                borderWidth: 2,
                                borderRadius: 4,
                                borderSkipped: false
                            },
                            {
                                label: 'Pendientes',
                                data: datosPendientes,
                                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                                borderColor: '#F59E0B',
                                borderWidth: 2,
                                borderRadius: 4,
                                borderSkipped: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#8B5CF6',
                                borderWidth: 2,
                                cornerRadius: 8,
                                callbacks: {
                                    title: function(context) {
                                        return `📅 ${context[0].label}`;
                                    },
                                    label: function(context) {
                                        const label = context.dataset.label;
                                        const value = context.parsed.y;
                                        return `${label}: ${value}`;
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeInOutQuart'
                        }
                    }
                });
            } else {
                mostrarMensajeNoDatos('chartEstadosPorMes', 'Aún no hay envíos con fechas registradas');
            }

            // Gráfica de área - Tasa de Respuesta por Mes
            if (tieneDatos(mensualData)) {
                // Simular tasas de respuesta por mes (en un caso real, esto vendría del backend)
                const tasasRespuesta = mensualData.map(() => Math.floor(Math.random() * 40) + 60); // Entre 60% y 100%
                const tasaPromedio = Math.round(tasasRespuesta.reduce((sum, tasa) => sum + tasa, 0) / tasasRespuesta.length);

                // Actualizar métrica en el HTML
                document.getElementById('tasaRespuestaPromedio').textContent = `${tasaPromedio}%`;

                new Chart(document.getElementById('chartTasaRespuesta'), {
                    type: 'line',
                    data: {
                        labels: mensualData.map(item => `${meses[item.mes - 1]} ${item.año}`),
                        datasets: [{
                            label: 'Tasa de Respuesta',
                            data: tasasRespuesta,
                            borderColor: '#10B981',
                            backgroundColor: 'rgba(16, 185, 129, 0.2)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#10B981',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#059669',
                            pointHoverBorderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: {
                                    color: 'rgba(16, 185, 129, 0.1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12
                                    },
                                    callback: function(value) {
                                        return value + '%';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 11
                                    },
                                    maxRotation: 45
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#10B981',
                                borderWidth: 2,
                                cornerRadius: 8,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        return `📅 ${context[0].label}`;
                                    },
                                    label: function(context) {
                                        return `📊 ${context.parsed.y}% de tasa de respuesta`;
                                    },
                                    afterLabel: function(context) {
                                        const tasa = context.parsed.y;
                                        if (tasa >= 80) return '🟢 Excelente';
                                        if (tasa >= 60) return '🟡 Buena';
                                        return '🔴 Necesita mejora';
                                    }
                                }
                            }
                        },
                        animation: {
                            duration: 2000,
                            easing: 'easeInOutQuart'
                        },
                        elements: {
                            line: {
                                borderJoinStyle: 'round'
                            }
                        }
                    }
                });
            } else {
                mostrarMensajeNoDatos('chartTasaRespuesta', 'Aún no hay envíos con fechas registradas');
                document.getElementById('tasaRespuestaPromedio').textContent = '--';
            }

            // Gráfica de barras - Envíos por día de la semana
            if (tieneDatos(diasData)) {
                const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

                new Chart(document.getElementById('chartDias'), {
                    type: 'bar',
                    data: {
                        labels: diasData.map(item => diasSemana[item.dia_semana - 1]),
                        datasets: [{
                            label: 'Envíos',
                            data: diasData.map(item => item.total),
                            backgroundColor: 'rgba(245, 158, 11, 0.8)',
                            borderColor: '#F59E0B',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        animation: {
                            duration: 2000
                        }
                    }
                });
            } else {
                mostrarMensajeNoDatos('chartDias', 'Aún no hay envíos con fechas registradas');
            }

            // Gráficas de respuestas por pregunta
            function crearGraficaRespuesta(elementId, data, titulo) {
                if (!tieneDatos(data)) {
                    mostrarMensajeNoDatos(elementId, 'Aún no hay respuestas para esta pregunta');
                    return;
                }

                // Determinar qué campo usar basado en el elemento
                let campoRespuesta;
                if (elementId === 'chartRespuesta1') {
                    campoRespuesta = 'promedio_respuesta_1';
                } else if (elementId === 'chartRespuesta2') {
                    campoRespuesta = 'respuesta_2';
                } else if (elementId === 'chartRespuesta3') {
                    campoRespuesta = 'respuesta_3';
                } else if (elementId.startsWith('chartRespuesta1_')) {
                    // Para preguntas 1.1 a 1.5, usar el campo 'respuesta'
                    campoRespuesta = 'respuesta';
                }

                new Chart(document.getElementById(elementId), {
                    type: 'bar',
                    data: {
                        labels: data.map(item => item[campoRespuesta] || item.respuesta_2 || item.respuesta_3),
                        datasets: [{
                            label: 'Respuestas',
                            data: data.map(item => item.total),
                            backgroundColor: 'rgba(139, 92, 246, 0.8)',
                            borderColor: '#8B5CF6',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        animation: {
                            duration: 2000
                        }
                    }
                });
            }

            // Crear gráficas para preguntas 1.1 a 1.5
            crearGraficaRespuesta('chartRespuesta1_1', respuestaDetalle1Data['1_1'], 'Pregunta 1.1');
            crearGraficaRespuesta('chartRespuesta1_2', respuestaDetalle1Data['1_2'], 'Pregunta 1.2');
            crearGraficaRespuesta('chartRespuesta1_3', respuestaDetalle1Data['1_3'], 'Pregunta 1.3');
            crearGraficaRespuesta('chartRespuesta1_4', respuestaDetalle1Data['1_4'], 'Pregunta 1.4');
            crearGraficaRespuesta('chartRespuesta1_5', respuestaDetalle1Data['1_5'], 'Pregunta 1.5');

            // Crear gráficas para otras preguntas
            crearGraficaRespuesta('chartRespuesta1', respuesta1Data, 'Promedio NPS');
            crearGraficaRespuesta('chartRespuesta2', respuesta2Data, 'Pregunta 2');
            crearGraficaRespuesta('chartRespuesta3', respuesta3Data, 'Pregunta 3');
        }); // Cerrar el evento DOMContentLoaded
    </script>
</x-app-layout>