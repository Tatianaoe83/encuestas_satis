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
                    </div>
                </div>

                <div class="bg-green-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-green-100 text-xs sm:text-sm font-medium">Respondido por el usuario</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $enviosCompletados }}</p>

                        </div>
                    </div>
                </div>

                <div class="bg-red-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-red-100 text-xs sm:text-sm font-medium">Sin respuesta</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $enviosCancelados }}</p>

                        </div>
                    </div>
                </div>

                <div class="bg-yellow-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            
                            <p class="text-yellow-100 text-xs sm:text-sm font-medium">Usuario respondiendo</p>
                            <p class="text-yellow-100 text-xs sm:text-sm font-medium">Enviado por Whatsapp</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $enviosPendientes }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-indigo-100 text-xs sm:text-sm font-medium truncate">Tasa de Respuesta</p>
                            <p class="text-2xl sm:text-3xl font-bold mt-1 sm:mt-2">{{ $tasaRespuesta }}%</p>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección 1: Métricas Clave y NPS -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 lg:gap-10 mb-8 sm:mb-12">
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

                <!-- Gráfica de Gauge - NPS -->
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
                    <div class="p-4 sm:p-6 lg:p-8">
                        <!-- Gauge del NPS -->
                        <div class="relative h-64 sm:h-72 lg:h-80">
                            <div id="gaugeNPS"></div>
                        </div>

                        <!-- Información adicional del NPS -->
                        <div class="mt-4 sm:mt-6 grid grid-cols-3 gap-1 sm:gap-2 lg:gap-4">
                            <div class="text-center">
                                <div class="text-base sm:text-lg lg:text-xl font-bold text-green-600">{{ $npsData['promotores'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Promotores (9-10)</div>
                                <div class="text-xs text-green-500 font-medium">{{ $npsData['porcentaje_promotores'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-base sm:text-lg lg:text-xl font-bold text-yellow-600">{{ $npsData['pasivos'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Pasivos (7-8)</div>
                                <div class="text-xs text-yellow-500 font-medium">{{ $npsData['porcentaje_pasivos'] }}%</div>
                            </div>
                            <div class="text-center">
                                <div class="text-base sm:text-lg lg:text-xl font-bold text-red-600">{{ $npsData['detractores'] }}</div>
                                <div class="text-xs text-gray-600 leading-tight">Detractores (0-6)</div>
                                <div class="text-xs text-red-500 font-medium">{{ $npsData['porcentaje_detractores'] }}%</div>
                            </div>
                        </div>

                        <!-- Fórmula del NPS -->
                        <div class="mt-3 sm:mt-4 text-center p-2 sm:p-3 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-600 mb-1">Fórmula NPS:</div>
                            <div class="text-xs sm:text-sm font-medium text-gray-800">
                                {{ $npsData['porcentaje_promotores'] }}% - {{ $npsData['porcentaje_detractores'] }}% = {{ $npsData['nps_score'] }}
                                </div>
                            <div class="text-xs text-gray-500 mt-1">{{ $npsData['total'] }} respuestas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Asesores -->
            <div class="mb-8 sm:mb-12">
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 sm:p-8 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">👥 Detalles de los Asesores</h3>
                        <div class="flex items-center space-x-2">
                        <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                        <span class="text-sm text-gray-600">NPS y Promedio de Servicio</span>
                        </div>
                    </div>
                    </div>

                    <div class="p-6 sm:p-8 overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                        <tr>
                            <th scope="col" class="px-4 py-3">Asesor Comercial</th>
                            <th scope="col" class="px-4 py-3 text-center">Total Envíos</th>
                            <th scope="col" class="px-4 py-3 text-center">Promedio de los Servicios</th>
                            <th scope="col" class="px-4 py-3 text-center">NPS</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        @forelse($topAsesores as $asesor)
                            <tr class="hover:bg-purple-50 transition">
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $asesor->asesor_comercial ?? 'Sin asignar' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ $asesor->total_envios }}
                            </td>
                            <td class="px-4 py-3 text-center text-purple-700 font-semibold">
                                {{ number_format($asesor->promedio_servicio, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center font-bold 
                                {{ $asesor->nps_score >= 50 ? 'text-green-600' : ($asesor->nps_score >= 0 ? 'text-yellow-500' : 'text-red-600') }}">
                                {{ $asesor->nps_score }}%
                            </td>
                            </tr>
                        @empty
                            <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                No hay datos disponibles.
                            </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
 
            <!-- Sección 5: Análisis de Respuestas por Pregunta -->
            <div class="mb-8 sm:mb-12">
                <div class="bg-white rounded-lg sm:rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-6 border-b border-gray-100 bg-gradient-to-r from-slate-50 to-gray-50">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div>
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">🔍 Análisis de Respuestas por Pregunta - Gauge Series</h3>
                                <p class="text-gray-600 mt-1 text-sm sm:text-base">Visualización tipo velocímetro para cada pregunta de la encuesta</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-slate-500 rounded-full"></span>
                                <span class="text-xs sm:text-sm text-gray-600">Insights</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <!-- Selector de Asesores -->
                        <div class="mb-6 sm:mb-8">
                            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-lg p-4 border border-indigo-200">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <h4 class="text-sm font-semibold text-indigo-900 flex items-center">
                                            <span class="w-5 h-5 bg-indigo-100 rounded-full flex items-center justify-center mr-2 text-indigo-600 text-xs">👤</span>
                                            Filtrar por Asesor
                                        </h4>
                                        <p class="text-xs text-indigo-700 mt-1">Selecciona un asesor para ver sus métricas específicas</p>
                                    </div>
                                    <div class="sm:w-64">
                                        <select id="selectorAsesor" class="w-full px-3 py-2 border border-indigo-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                            <option value="">Todos los asesores</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Calidad del Producto (Preguntas 1.1 a 1.5) -->
                        <div class="mb-8 sm:mb-12">
                            <div class="flex items-center justify-between mb-3 sm:mb-4">
                                <h4 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center">
                                    <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-blue-600 font-bold text-sm">📊</span>
                                    Calidad del Producto - Gauge Analysis
                                </h4>
                                <button 
                                    id="btnVerUltimosEnvios"
                                    onclick="abrirPopupUltimosEnvios()" 
                                    class="bg-gray-400 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 cursor-not-allowed"
                                    disabled
                                    title="Selecciona un asesor para ver sus envíos"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Ver Últimos Envíos
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                                <!-- Pregunta 1.1 -->
                                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-blue-900">1.1 - Calidad General</h5>
                                        <div class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">
                                            Escala 1-10
                                        </div>
                                    </div>
                                    <div class="relative h-64">
                                        <div id="gaugeRespuesta1_1"></div>
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
                                    <div class="relative h-64">
                                        <div id="gaugeRespuesta1_2"></div>
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
                                    <div class="relative h-64">
                                        <div id="gaugeRespuesta1_3"></div>
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
                                    <div class="relative h-64">
                                        <div id="gaugeRespuesta1_4"></div>
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
                                    <div class="relative h-64">
                                        <div id="gaugeRespuesta1_5"></div>
                                    </div>
                                </div>

                                <!-- Promedio NPS -->
                                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center justify-between mb-3">
                                        <h5 class="text-sm font-semibold text-green-900">Promedio de calidad de servicios</h5>
                                        <div class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">
                                            Promedio
                                        </div>
                                    </div>
                                    <div class="relative h-64">
                                        <div id="gaugeRespuesta1"></div>
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
                                <div class="relative h-64">
                                    <div id="gaugeRespuesta2"></div>
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
                                <div class="max-h-80 overflow-y-auto">
                                    <div id="listaSugerencias">
                                        <div class="text-center text-gray-500 py-8">
                                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600 mx-auto mb-2"></div>
                                            Cargando sugerencias...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- fin de la sección 5 -->
            

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
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">Distribución de envíos completados, pendientes y sin respuesta por mes</p>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="mb-4 flex flex-wrap items-center justify-center gap-3 sm:gap-6">
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <span class="text-sm text-gray-600 font-medium">Completados</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                                <span class="text-sm text-gray-600 font-medium">Pendientes</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-red-500 rounded"></div>
                                <span class="text-sm text-gray-600 font-medium">Sin respuesta</span>
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

        </div>
    </div>

    <!-- Scripts para Highcharts y Chart.js -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/themes/adaptive.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', function() {
            // Datos para las gráficas
            const estadosData = @json($enviosPorEstado);
            const asesoresData = @json($topAsesores);
            const mensualData = @json($enviosPorMes);
            const estadosPorMesData = @json($enviosPorEstadoPorMes);
            const diasData = @json($enviosPorDia);
            const estadosPorDiaData = @json($enviosPorEstadoPorDia);
            const respuesta1Data = @json($respuestasPregunta1);
            const respuesta2Data = @json($respuestasPregunta2);
            const respuesta3Data = @json($respuestasPregunta3);
            const respuestaDetalle1Data = @json($respuestasDetalle1);

            // Función para poblar el selector de asesores
            function poblarSelectorAsesores() {
                const selector = document.getElementById('selectorAsesor');
                const asesoresUnicos = new Set();
                
                // Extraer asesores únicos de múltiples fuentes de datos
                const fuentesDatos = [
                    asesoresData,
                    respuesta1Data,
                    respuesta2Data,
                    respuesta3Data,
                    respuestaDetalle1Data['1_1'],
                    respuestaDetalle1Data['1_2'],
                    respuestaDetalle1Data['1_3'],
                    respuestaDetalle1Data['1_4'],
                    respuestaDetalle1Data['1_5']
                ];
                
                fuentesDatos.forEach(datos => {
                    if (datos && Array.isArray(datos) && datos.length > 0) {
                        datos.forEach(item => {
                            // Buscar asesor en diferentes campos posibles
                            const asesor = item.asesor_comercial || 
                                         item.asesor || 
                                         item.comercial ||
                                         item.asesor_nombre;
                            if (asesor && asesor.trim() !== '') {
                                asesoresUnicos.add(asesor.trim());
                            }
                        });
                    }
                });
                
                // Limpiar opciones existentes (excepto "Todos los asesores")
                selector.innerHTML = '<option value="">Todos los asesores</option>';
                
                // Agregar asesores únicos
                const asesoresArray = Array.from(asesoresUnicos).sort();
              
                
                asesoresArray.forEach(asesor => {
                    const option = document.createElement('option');
                    option.value = asesor;
                    option.textContent = asesor;
                    selector.appendChild(option);
                });
                
            
            }

            // Función para filtrar datos por asesor
            function filtrarDatosPorAsesor(datos, asesor) {
                if (!asesor || asesor === '') {
                  
                    return datos || [];
                }
                
                if (!datos || !Array.isArray(datos)) {
                
                    return [];
                }
                
             
                
            
                
                const datosFiltrados = datos.filter(item => {
                    if (!item || typeof item !== 'object') {
                        return false;
                    }
                    
                    // Buscar asesor en diferentes campos posibles
                    const asesorItem = item.asesor_comercial || 
                                     item.asesor || 
                                     item.comercial ||
                                     item.asesor_nombre ||
                                     item.nombre_asesor ||
                                     item.asesor_comercial_nombre;
                    
                    if (asesorItem) {}
                    
                    const coincide = asesorItem && asesorItem.toString().trim().toLowerCase() === asesor.trim().toLowerCase();
                    
                    if (coincide) {}
                    
                    return coincide;
                });
                
             
                
                // Si no se encontraron registros, mostrar todos los asesores disponibles
                if (datosFiltrados.length === 0 && datos.length > 0) {
                    const asesoresDisponibles = [...new Set(datos.map(item => {
                        const asesor = item.asesor_comercial || item.asesor || item.comercial || 
                                     item.asesor_nombre || item.nombre_asesor || item.asesor_comercial_nombre;
                        return asesor;
                    }).filter(asesor => asesor))];
                 
                }
                
                return datosFiltrados;
            }

            // Función para actualizar todos los gauges y sugerencias
            function actualizarVisualizaciones(asesorSeleccionado) {
             
                
                // Filtrar datos
                const respuesta1Filtrada = filtrarDatosPorAsesor(respuesta1Data, asesorSeleccionado);
                const respuesta2Filtrada = filtrarDatosPorAsesor(respuesta2Data, asesorSeleccionado);
                const respuesta3Filtrada = filtrarDatosPorAsesor(respuesta3Data, asesorSeleccionado);
                const respuestaDetalle1Filtrada = {};
                
                // Filtrar datos detallados
                Object.keys(respuestaDetalle1Data).forEach(key => {
                    respuestaDetalle1Filtrada[key] = filtrarDatosPorAsesor(respuestaDetalle1Data[key], asesorSeleccionado);
                });

             

                // Verificar si hay datos para el asesor seleccionado
                const totalDatosFiltrados = respuesta1Filtrada.length + respuesta2Filtrada.length + respuesta3Filtrada.length +
                    (respuestaDetalle1Filtrada['1_1']?.length || 0) + (respuestaDetalle1Filtrada['1_2']?.length || 0) +
                    (respuestaDetalle1Filtrada['1_3']?.length || 0) + (respuestaDetalle1Filtrada['1_4']?.length || 0) +
                    (respuestaDetalle1Filtrada['1_5']?.length || 0);

                if (asesorSeleccionado && totalDatosFiltrados === 0) {
                 
                    // Mostrar mensaje de no datos en todos los gauges
                    const gaugeIds = ['gaugeRespuesta1_1', 'gaugeRespuesta1_2', 'gaugeRespuesta1_3', 'gaugeRespuesta1_4', 'gaugeRespuesta1_5', 'gaugeRespuesta1', 'gaugeRespuesta2'];
                    gaugeIds.forEach(gaugeId => {
                        const element = document.getElementById(gaugeId);
                        if (element) {
                            limpiarGrafica(gaugeId);
                            mostrarMensajeNoDatos(gaugeId, `No hay datos disponibles para el asesor "${asesorSeleccionado}"`);
                        }
                    });
                    return;
                }

                // Actualizar gauges solo si los elementos existen
                const gaugeElements = [
                    { id: 'gaugeRespuesta1_1', data: respuestaDetalle1Filtrada['1_1'], title: 'Calidad General' },
                    { id: 'gaugeRespuesta1_2', data: respuestaDetalle1Filtrada['1_2'], title: 'Puntualidad' },
                    { id: 'gaugeRespuesta1_3', data: respuestaDetalle1Filtrada['1_3'], title: 'Trato Asesor' },
                    { id: 'gaugeRespuesta1_4', data: respuestaDetalle1Filtrada['1_4'], title: 'Precio' },
                    { id: 'gaugeRespuesta1_5', data: respuestaDetalle1Filtrada['1_5'], title: 'Rapidez' },
                    { id: 'gaugeRespuesta1', data: respuesta1Filtrada, title: 'Promedio de los Servicios' },
                    { id: 'gaugeRespuesta2', data: respuesta2Filtrada, title: 'Recomendación' }
                ];

                gaugeElements.forEach(gauge => {
                    const element = document.getElementById(gauge.id);
                    if (element) {
                        // Limpiar gráfica existente
                        limpiarGrafica(gauge.id);
                        // Crear nueva gráfica
                        crearGaugeRespuesta(gauge.id, gauge.data, gauge.title);
                    } else {
                     
                    }
                });
                
                // Actualizar lista de sugerencias
                crearListaSugerencias(respuesta3Filtrada);
            }

            // Configuración común para Chart.js
            Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
            Chart.defaults.color = '#1b222eff';

            // Función para verificar si hay datos
            function tieneDatos(data) {
                return data && data.length > 0;
            }

            // Función para limpiar gráficas existentes
            function limpiarGrafica(elementId) {
                const elemento = document.getElementById(elementId);
                if (!elemento) {
                 
                    return;
                }
                
                // Limpiar el contenido del elemento
                elemento.innerHTML = '';
                
                // Si existe una instancia de Highcharts, destruirla
                if (elementId && window.Highcharts && window.Highcharts.charts) {
                    const chartIndex = window.Highcharts.charts.findIndex(chart => 
                        chart && chart.renderTo && chart.renderTo.id === elementId
                    );
                    if (chartIndex !== -1 && window.Highcharts.charts[chartIndex]) {
                        window.Highcharts.charts[chartIndex].destroy();
                    }
                }
            }

            // Función para mostrar mensaje de no datos
            function mostrarMensajeNoDatos(elementId, mensaje) {
                const elemento = document.getElementById(elementId);
                if (!elemento) {
                 
                    return;
                }
                
                // Limpiar el contenido del elemento
                elemento.innerHTML = `
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
            if (tieneDatos(estadosPorMesData)) {
                // Crear datos para la gráfica de estados por mes usando datos reales
                const mesesUnicos = [...new Set(estadosPorMesData.map(item => `${meses[item.mes - 1]} ${item.año}`))];
                const labelsEstadosPorMes = mesesUnicos.sort();

                // Procesar datos reales por estado
                const datosCompletados = [];
                const datosPendientes = [];
                const datosSinRespuesta = [];

                mesesUnicos.forEach(mesLabel => {
                    const [mesNombre, año] = mesLabel.split(' ');
                    const mesNumero = meses.indexOf(mesNombre) + 1;
                    
                    // Buscar datos para este mes específico
                    const datosMes = estadosPorMesData.filter(item => 
                        item.mes === mesNumero && item.año == año
                    );

                    // Inicializar contadores
                    let completados = 0;
                    let pendientes = 0;
                    let sinRespuesta = 0;

                    // Sumar por estado
                    datosMes.forEach(item => {
                        switch(item.estado_grupo) {
                            case 'completado':
                                completados += item.total;
                                break;
                            case 'pendiente':
                                pendientes += item.total;
                                break;
                            case 'sin_respuesta':
                                sinRespuesta += item.total;
                                break;
                        }
                    });

                    datosCompletados.push(completados);
                    datosPendientes.push(pendientes);
                    datosSinRespuesta.push(sinRespuesta);
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
                                label: 'Pendientes',
                                data: datosPendientes,
                                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                                borderColor: '#F59E0B',
                                borderWidth: 2,
                                borderRadius: 4,
                                borderSkipped: false
                            },
                            {
                                label: 'Sin respuesta',
                                data: datosSinRespuesta,
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: '#EF4444',
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

            // Gráfica de barras apiladas - Envíos por día de la semana
            if (tieneDatos(estadosPorDiaData)) {
                const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

                // Procesar datos reales por estado
                const datosCompletados = [];
                const datosPendientes = [];
                const datosSinRespuesta = [];

                // Inicializar arrays para los 7 días de la semana
                for (let i = 1; i <= 7; i++) {
                    datosCompletados.push(0);
                    datosPendientes.push(0);
                    datosSinRespuesta.push(0);
                }

                // Procesar datos por día y estado
                estadosPorDiaData.forEach(item => {
                    const indiceDia = item.dia_semana - 1; // Convertir a índice 0-based
                    
                    switch(item.estado_grupo) {
                        case 'completado':
                            datosCompletados[indiceDia] += item.total;
                            break;
                        case 'pendiente':
                            datosPendientes[indiceDia] += item.total;
                            break;
                        case 'sin_respuesta':
                            datosSinRespuesta[indiceDia] += item.total;
                            break;
                    }
                });

                new Chart(document.getElementById('chartDias'), {
                    type: 'bar',
                    data: {
                        labels: diasSemana,
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
                                label: 'Pendientes',
                                data: datosPendientes,
                                backgroundColor: 'rgba(245, 158, 11, 0.8)',
                                borderColor: '#F59E0B',
                                borderWidth: 2,
                                borderRadius: 4,
                                borderSkipped: false
                            },
                            {
                                label: 'Sin respuesta',
                                data: datosSinRespuesta,
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                                borderColor: '#EF4444',
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
                            mode: 'index',
                            intersect: false
                        },
                        scales: {
                            x: {
                                stacked: true,
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                stacked: true,
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
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

            // Función para crear el gauge del NPS
            function crearGaugeNPS() {
                const npsScore = {{ $npsData['nps_score'] }};
                const totalRespuestas = {{ $npsData['total'] }};
                
                // Determinar el color basado en el score NPS
                let colorGauge;
                let colorBandas;
                
                if (npsScore >= 50) {
                    colorGauge = '#28CA42'; // Verde - Excelente
                    colorBandas = [
                        { from: -100, to: 0, color: '#FF5F57' },    // Rojo - Detractores
                        { from: 0, to: 30, color: '#FFBD2E' },     // Amarillo - Neutro
                        { from: 30, to: 50, color: '#28CA42' },    // Verde - Bueno
                        { from: 50, to: 100, color: '#28CA42' }    // Verde - Excelente
                    ];
                } else if (npsScore >= 0) {
                    colorGauge = '#FFBD2E'; // Amarillo - Bueno
                    colorBandas = [
                        { from: -100, to: 0, color: '#FF5F57' },    // Rojo - Detractores
                        { from: 0, to: 30, color: '#FFBD2E' },     // Amarillo - Neutro
                        { from: 30, to: 50, color: '#28CA42' },    // Verde - Bueno
                        { from: 50, to: 100, color: '#28CA42' }    // Verde - Excelente
                    ];
                } else {
                    colorGauge = '#FF5F57'; // Rojo - Necesita mejora
                    colorBandas = [
                        { from: -100, to: 0, color: '#FF5F57' },    // Rojo - Detractores
                        { from: 0, to: 30, color: '#FFBD2E' },     // Amarillo - Neutro
                        { from: 30, to: 50, color: '#28CA42' },    // Verde - Bueno
                        { from: 50, to: 100, color: '#28CA42' }    // Verde - Excelente
                    ];
                }
                
                Highcharts.chart('gaugeNPS', {
                    chart: {
                        type: 'gauge',
                        plotBackgroundColor: null,
                        plotBackgroundImage: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: window.innerWidth < 640 ? 250 : window.innerWidth < 1024 ? 280 : 300,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: 'NPS Score',
                        style: {
                            fontSize: window.innerWidth < 640 ? '14px' : window.innerWidth < 1024 ? '16px' : '18px',
                            fontWeight: 'bold',
                            color: '#333'
                        },
                        y: 20
                    },
                    pane: {
                        startAngle: -90,
                        endAngle: 90,
                        background: null,
                        center: ['50%', '75%'],
                        size: '120%'
                    },
                    yAxis: {
                        min: -100,
                        max: 100,
                        stops: [
                            [0.0, '#FF5F57'], // Rojo
                            [0.3, '#FFBD2E'], // Amarillo
                            [0.5, '#28CA42'], // Verde
                            [1.0, '#28CA42']  // Verde
                        ],
                        lineWidth: 0,
                        tickWidth: 0,
                        minorTickInterval: null,
                        tickAmount: 5,
                        title: {
                            y: -70,
                            text: 'NPS Score'
                        },
                        labels: {
                            y: 16,
                            distance: -20,
                            formatter: function() {
                                return this.value;
                            }
                        },
                        plotBands: colorBandas
                    },
                    series: [{
                        name: 'NPS Score',
                        data: [npsScore],
                        dataLabels: {
                            format: '{y}',
                            borderWidth: 0,
                            color: '#333333',
                            style: {
                                fontSize: window.innerWidth < 640 ? '18px' : window.innerWidth < 1024 ? '20px' : '24px',
                                fontWeight: 'bold',
                                textOutline: 'none'
                            },
                            y: 10
                        },
                        tooltip: {
                            valueSuffix: ' puntos'
                        },
                        dial: {
                            radius: '85%',
                            backgroundColor: colorGauge,
                            baseWidth: 15,
                            baseLength: '10%',
                            rearLength: '10%',
                            borderWidth: 0
                        },
                        pivot: {
                            backgroundColor: colorGauge,
                            radius: 8,
                            borderWidth: 0
                        }
                    }],
                    credits: {
                        enabled: false
                    }
                });
            }

            // Gráficas de respuestas por pregunta
            function crearGaugeRespuesta(elementId, data, titulo) {
                const elemento = document.getElementById(elementId);
                if (!elemento) {
                    console.error(`Elemento no encontrado: ${elementId}`);
                    return;
                }

                if (!tieneDatos(data)) {
                    mostrarMensajeNoDatos(elementId, 'Aún no hay respuestas para esta pregunta');
                    return;
                }

                // Determinar qué campo usar basado en el elemento
                let campoRespuesta;
                if (elementId === 'gaugeRespuesta1') {
                    campoRespuesta = 'promedio_respuesta_1';
                } else if (elementId === 'gaugeRespuesta2') {
                    campoRespuesta = 'respuesta_2';
                } else if (elementId === 'gaugeRespuesta3') {
                    campoRespuesta = 'respuesta_3';
                } else if (elementId.startsWith('gaugeRespuesta1_')) {
                    // Para preguntas 1.1 a 1.5, usar el campo 'respuesta'
                    campoRespuesta = 'respuesta';
                }

                // Calcular el valor promedio o el valor más alto para el gauge
                let valorGauge;
                let maxValor = 10; // Valor máximo por defecto para escala 1-10
                let colores = ['#FF5F57', '#FFBD2E', '#28CA42']; // Rojo, Amarillo, Verde

                if (elementId === 'gaugeRespuesta2') {
                        const totalRespuestas = data.reduce((sum, item) => sum + (item.total || 0), 0);
                        console.log(totalRespuestas);
                        const totalPositivas = data
                            .filter(item => {
                                const val = (item[campoRespuesta] || '').toString().trim().toLowerCase();
                                return ['sí', 'si', '1', 'true'].includes(val);
                            })
                            .reduce((sum, item) => sum + (item.total || 0), 0);
                        
                        valorGauge = totalRespuestas > 0 ? parseFloat(((totalPositivas / totalRespuestas) * 100).toFixed(2)) : 0;
                        maxValor = 100;
                } else if (elementId === 'gaugeRespuesta3') {
                    // Para pregunta de Mejoras (texto libre), usar porcentaje
                    const totalRespuestas = data.reduce((sum, item) => sum + item.total, 0);
                    const respuestasPositivas = data.find(item => 
                        item[campoRespuesta] === 'Sí' || 
                        item[campoRespuesta] === 'Si' || 
                        item[campoRespuesta] === '1' ||
                        item[campoRespuesta] === 'true'
                    );
                    valorGauge = respuestasPositivas ? (respuestasPositivas.total / totalRespuestas) * 100 : 0;
                    maxValor = 100;
                } else {
                    // Para preguntas 1-10, calcular promedio ponderado
                    const totalRespuestas = data.reduce((sum, item) => sum + item.total, 0);
                    let sumaPonderada = 0;
                    data.forEach(item => {
                        const valor = parseFloat(item[campoRespuesta]) || 0;
                        sumaPonderada += valor * item.total;
                    });
                    valorGauge = totalRespuestas > 0 ? parseFloat((sumaPonderada / totalRespuestas).toFixed(2)) : 0;
                }

                Highcharts.chart(elementId, {
                    chart: {
                        type: 'gauge',
                        plotBackgroundColor: null,
                        plotBackgroundImage: null,
                        plotBorderWidth: 0,
                        plotShadow: false,
                        height: 300,
                        backgroundColor: 'transparent'
                    },
                    title: {
                        text: titulo,
                        style: {
                            fontSize: '16px',
                            fontWeight: 'bold',
                            color: '#333'
                        },
                        y: 20
                    },
                    pane: {
                        startAngle: -90,
                        endAngle: 90,
                        background: null,
                        center: ['50%', '75%'],
                        size: '110%'
                    },
                    yAxis: {
                        min: 0,
                        max: maxValor,
                        stops: elementId === 'gaugeRespuesta2' ? [
                            [0.5, '#FF5F57'], // rojo (No)
                            [0.5, '#28CA42']  // verde (Sí)
                        ] : [
                            [0.1, '#FF5F57'], // rojo (0-2)
                            [0.5, '#FFBD2E'], // amarillo (2-6)
                            [0.9, '#28CA42']  // verde (6-10)
                        ],
                        lineWidth: 0,
                        tickWidth: 0,
                        minorTickInterval: null,
                        tickAmount: 2,
                        title: {
                            y: -70,
                            text: maxValor === 100 ? (elementId === 'gaugeRespuesta2' ? 'Recomendación' : 'Porcentaje') : 'Puntuación'
                        },
                        labels: {
                            y: 16,
                            distance: -20,
                            formatter: function() {
                                if (elementId === 'gaugeRespuesta2') {
                                    return this.value === 0 ? 'No' : this.value === 100 ? 'Sí' : this.value + '%';
                                }
                                return this.value + (maxValor === 100 ? '%' : '');
                            }
                        },
                        plotBands: elementId === 'gaugeRespuesta2' ? [{
                            from: 0,
                            to: maxValor * 0.5,
                            color: '#FF5F57', // rojo (No)
                            thickness: '20%',
                            outerRadius: '105%'
                        }, {
                            from: maxValor * 0.5,
                            to: maxValor,
                            color: '#28CA42', // verde (Sí)
                            thickness: '20%',
                            outerRadius: '105%'
                        }] : [{
                            from: 0,
                            to: maxValor * 0.2,
                            color: '#FF5F57', // rojo (0-2)
                            thickness: '20%',
                            outerRadius: '105%'
                        }, {
                            from: maxValor * 0.2,
                            to: maxValor * 0.6,
                            color: '#FFBD2E', // amarillo (2-6)
                            thickness: '20%',
                            outerRadius: '105%'
                        }, {
                            from: maxValor * 0.6,
                            to: maxValor,
                            color: '#28CA42', // verde (6-10)
                            thickness: '20%',
                            outerRadius: '105%'
                        }]
                    },
                    series: [{
                        name: titulo,
                        data: [valorGauge],
                        dataLabels: {
                            format: elementId === 'gaugeRespuesta2' ? 
                                (valorGauge >= 50 ? 'Sí' : 'No') : 
                                '{y}' + (maxValor === 100 ? '%' : ''),
                            borderWidth: 0,
                            color: '#333333',
                            style: {
                                fontSize: '20px',
                                fontWeight: 'bold',
                                textOutline: 'none'
                            },
                            y: 10
                        },
                        tooltip: {
                            valueSuffix: maxValor === 100 ? '%' : ''
                        },
                        dial: {
                            radius: '80%',
                            backgroundColor: '#333',
                            baseWidth: 15,
                            baseLength: '10%',
                            rearLength: '10%',
                            borderWidth: 0
                        },
                        pivot: {
                            backgroundColor: '#333',
                            radius: 8,
                            borderWidth: 0
                        }
                    }],
                    credits: {
                        enabled: false
                    }
                });
            }

            // Función para crear lista de sugerencias
            function crearListaSugerencias(data) {
                const contenedor = document.getElementById('listaSugerencias');
                
                if (!tieneDatos(data)) {
                    contenedor.innerHTML = `
                        <div class="text-center text-gray-500 py-8">
                            <div class="text-4xl mb-2">💭</div>
                            <p>Aún no hay sugerencias registradas</p>
                        </div>
                    `;
                    return;
                }

                let html = '<div class="space-y-3">';
                
                data.forEach((item, index) => {
                    const sugerencia = item.respuesta_3 || item.sugerencia || 'Sin sugerencia específica';
                    const nombreCliente = item.nombre_cliente || item.cliente_nombre || 'Cliente anónimo';
                    
                    html += `
                        <div class="bg-white rounded-lg p-4 border border-purple-200 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-purple-600 font-bold text-sm">${index + 1}</span>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-900 text-sm leading-relaxed">${sugerencia}</p>
                                    <div class="mt-2 flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            👤 ${nombreCliente}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                contenedor.innerHTML = html;
            }

          
            
          
            
            // Debug: buscar registros que contengan "PROSER"
            const fuentesDatos = [
                {nombre: 'respuesta1Data', datos: respuesta1Data},
                {nombre: 'respuesta2Data', datos: respuesta2Data},
                {nombre: 'respuesta3Data', datos: respuesta3Data},
                {nombre: 'respuestaDetalle1Data[1_1]', datos: respuestaDetalle1Data['1_1']},
                {nombre: 'respuestaDetalle1Data[1_2]', datos: respuestaDetalle1Data['1_2']},
                {nombre: 'respuestaDetalle1Data[1_3]', datos: respuestaDetalle1Data['1_3']},
                {nombre: 'respuestaDetalle1Data[1_4]', datos: respuestaDetalle1Data['1_4']},
                {nombre: 'respuestaDetalle1Data[1_5]', datos: respuestaDetalle1Data['1_5']}
            ];
            
            fuentesDatos.forEach(fuente => {
                if (fuente.datos && Array.isArray(fuente.datos)) {
                  
                    fuente.datos.forEach((item, index) => {
                        const camposAsesor = [
                            item.asesor_comercial,
                            item.asesor,
                            item.comercial,
                            item.asesor_nombre,
                            item.nombre_asesor,
                            item.asesor_comercial_nombre
                        ].filter(Boolean);
                        
                        if (camposAsesor.length > 0) {
                            
                        }
                    });
                }
            });
            
            // Debug: búsqueda específica de PROSER
            const buscarAsesor = (datos, nombreAsesor) => {
                if (!datos || !Array.isArray(datos)) return [];
                
                return datos.filter(item => {
                    const todosLosValores = Object.values(item).map(val => 
                        typeof val === 'string' ? val.toLowerCase() : String(val).toLowerCase()
                    );
                    return todosLosValores.some(val => val.includes(nombreAsesor.toLowerCase()));
                });
            };
            
            // Poblar selector de asesores
            poblarSelectorAsesores();
            
            // Crear gauge del NPS
            crearGaugeNPS();
            
            // Crear visualizaciones iniciales (todos los asesores) con un pequeño delay
            setTimeout(() => {
                actualizarVisualizaciones('');
            }, 100);
            
            // Event listener para el selector de asesores
            document.getElementById('selectorAsesor').addEventListener('change', function() {
                const asesorSeleccionado = this.value;
                actualizarVisualizaciones(asesorSeleccionado);
                actualizarEstadoBotonVerEnvios(asesorSeleccionado);
            });
        }); // Cerrar el evento DOMContentLoaded

        // Función para actualizar el estado del botón "Ver Últimos Envíos"
        function actualizarEstadoBotonVerEnvios(asesorSeleccionado) {
            const boton = document.getElementById('btnVerUltimosEnvios');
            
            if (asesorSeleccionado && asesorSeleccionado !== '') {
                // Habilitar el botón
                boton.disabled = false;
                boton.className = 'bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2';
                boton.title = `Ver últimos envíos de ${asesorSeleccionado}`;
            } else {
                // Deshabilitar el botón
                boton.disabled = true;
                boton.className = 'bg-gray-400 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2 cursor-not-allowed';
                boton.title = 'Selecciona un asesor para ver sus envíos';
            }
        }

        // Función para abrir el popup de últimos envíos
        function abrirPopupUltimosEnvios() {
            const asesorSeleccionado = document.getElementById('selectorAsesor').value;
            
            if (!asesorSeleccionado || asesorSeleccionado === '') {
                alert('Por favor selecciona un asesor primero');
                return;
            }
            
            // Mostrar el modal
            document.getElementById('modalUltimosEnvios').classList.remove('hidden');
            
            // Cargar los datos
            cargarUltimosEnvios(asesorSeleccionado);
        }

        // Función para cerrar el popup
        function cerrarPopupUltimosEnvios() {
            document.getElementById('modalUltimosEnvios').classList.add('hidden');
        }

        // Función para cargar los últimos envíos
        async function cargarUltimosEnvios(asesorSeleccionado) {
            const contenedor = document.getElementById('contenidoUltimosEnvios');
            contenedor.innerHTML = '<div class="flex justify-center items-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div></div>';

            try {
                const url = `{{ route("resultados.ultimos-envios-calidad") }}?asesor=${encodeURIComponent(asesorSeleccionado)}`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.total === 0) {
                    contenedor.innerHTML = `<div class="text-center py-8 text-gray-500">No hay envíos completados para el asesor "${asesorSeleccionado}".</div>`;
                    return;
                }

                let html = `
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-blue-600 font-bold text-sm">👤</span>
                            ${asesorSeleccionado}
                            <span class="ml-2 text-sm text-gray-500">(${data.total} envíos)</span>
                        </h3>
                    </div>
                    <div class="space-y-3">
                `;
                
                data.envios.forEach(envio => {
                    const fechaRespuesta = new Date(envio.fecha_respuesta).toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    html += `
                        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-medium text-gray-900">${envio.razon_social}</h4>
                                    <p class="text-sm text-gray-600">${envio.nombre_completo} - ${envio.puesto}</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-blue-600">${envio.promedio_respuesta_1}</div>
                                    <div class="text-xs text-gray-500">Promedio</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-5 gap-2 text-sm">
                                <div class="text-center">
                                    <div class="font-medium">${envio.respuesta_1_1 || '-'}</div>
                                    <div class="text-xs text-gray-500">Calidad</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium">${envio.respuesta_1_2 || '-'}</div>
                                    <div class="text-xs text-gray-500">Puntualidad</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium">${envio.respuesta_1_3 || '-'}</div>
                                    <div class="text-xs text-gray-500">Trato</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium">${envio.respuesta_1_4 || '-'}</div>
                                    <div class="text-xs text-gray-500">Precio</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-medium">${envio.respuesta_1_5 || '-'}</div>
                                    <div class="text-xs text-gray-500">Rapidez</div>
                                </div>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                Respondido: ${fechaRespuesta}
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                contenedor.innerHTML = html;
                
            } catch (error) {
                console.error('Error al cargar los últimos envíos:', error);
                contenedor.innerHTML = '<div class="text-center py-8 text-red-500">Error al cargar los datos. Inténtalo de nuevo.</div>';
            }
        }
    </script>

    <!-- Modal para mostrar los últimos envíos -->
    <div id="modalUltimosEnvios" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Header del modal -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <span class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 text-blue-600 font-bold text-sm">📊</span>
                        Últimos Envíos - Calidad del Producto
                    </h3>
                    <button 
                        onclick="cerrarPopupUltimosEnvios()" 
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Contenido del modal -->
                <div class="max-h-96 overflow-y-auto">
                    <div id="contenidoUltimosEnvios">
                        <!-- El contenido se carga dinámicamente aquí -->
                    </div>
                </div>
                
                <!-- Footer del modal -->
                <div class="mt-4 flex justify-end">
                    <button 
                        onclick="cerrarPopupUltimosEnvios()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>