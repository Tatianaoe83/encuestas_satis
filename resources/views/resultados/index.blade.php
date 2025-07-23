<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Análisis de Resultados') }}
                </h2>
                <p class="text-gray-600 mt-1">Visualiza y analiza los resultados de tus encuestas</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('resultados.detalle') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Detalle
                </a>
                <a href="{{ route('resultados.exportar') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de estadísticas generales mejoradas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Envíos</p>
                            <p class="text-3xl font-bold mt-2">{{ $totalEnvios }}</p>
                            <p class="text-blue-100 text-sm mt-1">+15% este mes</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M4 3a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h1v2a1 1 0 0 0 1.707.707L9.414 13H15a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H4Z" clip-rule="evenodd"/>
                                <path fill-rule="evenodd" d="M8.023 17.215c.033-.03.066-.062.098-.094L10.243 15H15a3 3 0 0 0 3-3V8h2a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-1v2a1 1 0 0 1-1.707.707L14.586 18H9a1 1 0 0 1-.977-.785Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Enviados</p>
                            <p class="text-3xl font-bold mt-2">{{ $enviosEnviados }}</p>
                            <p class="text-green-100 text-sm mt-1">+8% esta semana</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M3 5.983C3 4.888 3.895 4 5 4h14c1.105 0 2 .888 2 1.983v8.923a1.992 1.992 0 0 1-2 1.983h-6.6l-2.867 2.7c-.955.899-2.533.228-2.533-1.08v-1.62H5c-1.105 0-2-.888-2-1.983V5.983Zm5.706 3.809a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Zm2.585.002a1 1 0 1 1 .003 1.414 1 1 0 0 1-.003-1.414Zm5.415-.002a1 1 0 1 0-1.412 1.417 1 1 0 1 0 1.412-1.417Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Respondidos</p>
                            <p class="text-3xl font-bold mt-2">{{ $enviosRespondidos }}</p>
                            <p class="text-purple-100 text-sm mt-1">75% tasa de respuesta</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Pendientes</p>
                            <p class="text-3xl font-bold mt-2">{{ $enviosPendientes }}</p>
                            <p class="text-yellow-100 text-sm mt-1">Requieren atención</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4a1 1 0 1 0-2 0v4a1 1 0 0 0 .293.707l3 3a1 1 0 0 0 1.414-1.414L13 11.586V8Z" clip-rule="evenodd"/>
                              </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-indigo-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium">Tasa de Respuesta</p>
                            <p class="text-3xl font-bold mt-2">{{ $tasaRespuesta }}%</p>
                            <p class="text-indigo-100 text-sm mt-1">+5% vs mes anterior</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15v4m6-6v6m6-4v4m6-6v6M3 11l6-5 6 5 5.5-5.5"/>
                              </svg>
                              
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficas principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Gráfica de dona - Envíos por estado -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Envíos por Estado</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Distribución</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="chartEstados"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de barras - Top asesores -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Top 5 Asesores Comerciales</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Rendimiento</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="chartAsesores"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de línea - Envíos por mes -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Envíos por Mes</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-purple-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Tendencia</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="chartMensual"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Gráfica de barras - Envíos por día de la semana -->
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Envíos por Día de la Semana</h3>
                            <div class="flex items-center space-x-2">
                                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                                <span class="text-sm text-gray-600">Patrones</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="relative h-64">
                            <canvas id="chartDias"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficas de respuestas por pregunta -->
            <div class="mb-8">
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-900">Análisis de Respuestas por Pregunta</h3>
                        <p class="text-gray-600 mt-1">Distribución de respuestas para cada pregunta de la encuesta</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Respuestas Pregunta 1
                                </h4>
                                <div class="relative h-64">
                                    <canvas id="chartRespuesta1"></canvas>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Respuestas Pregunta 2
                                </h4>
                                <div class="relative h-64">
                                    <canvas id="chartRespuesta2"></canvas>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Respuestas Pregunta 3
                                </h4>
                                <div class="relative h-64">
                                    <canvas id="chartRespuesta3"></canvas>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-6">
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Respuestas Pregunta 4
                                </h4>
                                <div class="relative h-64">
                                    <canvas id="chartRespuesta4"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen ejecutivo -->
            <div class="bg-blue-50 rounded-xl shadow-lg border border-blue-100 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">Resumen Ejecutivo</h3>
                        <p class="text-gray-600">Análisis general de los resultados</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-blue-600 mb-2">{{ $tasaRespuesta }}%</div>
                        <div class="text-sm text-gray-600">Tasa de Respuesta Promedio</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 mb-2">4.2/5</div>
                        <div class="text-sm text-gray-600">Satisfacción Promedio</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600 mb-2">85%</div>
                        <div class="text-sm text-gray-600">Clientes Satisfechos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos para las gráficas
        const estadosData = @json($enviosPorEstado);
        const asesoresData = @json($topAsesores);
        const mensualData = @json($enviosPorMes);
        const diasData = @json($enviosPorDia);
        const respuesta1Data = @json($respuestasPregunta1);
        const respuesta2Data = @json($respuestasPregunta2);
        const respuesta3Data = @json($respuestasPregunta3);
        const respuesta4Data = @json($respuestasPregunta4);

        // Colores para las gráficas
        const colors = [
            '#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6',
            '#06B6D4', '#84CC16', '#F97316', '#EC4899', '#6366F1'
        ];

        // Configuración común para Chart.js
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';
        Chart.defaults.color = '#6B7280';

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
                        backgroundColor: colors.slice(0, estadosData.length),
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
        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        if (tieneDatos(mensualData)) {
            new Chart(document.getElementById('chartMensual'), {
                type: 'line',
                data: {
                    labels: mensualData.map(item => `${meses[item.mes - 1]} ${item.año}`),
                    datasets: [{
                        label: 'Envíos',
                        data: mensualData.map(item => item.total),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10B981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
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
            mostrarMensajeNoDatos('chartMensual', 'Aún no hay envíos con fechas registradas');
        }

        // Gráfica de barras - Envíos por día de la semana
        if (tieneDatos(diasData)) {
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

            new Chart(document.getElementById(elementId), {
                type: 'bar',
                data: {
                    labels: data.map(item => item.respuesta_1 || item.respuesta_2 || item.respuesta_3 || item.respuesta_4),
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

        crearGraficaRespuesta('chartRespuesta1', respuesta1Data, 'Pregunta 1');
        crearGraficaRespuesta('chartRespuesta2', respuesta2Data, 'Pregunta 2');
        crearGraficaRespuesta('chartRespuesta3', respuesta3Data, 'Pregunta 3');
        crearGraficaRespuesta('chartRespuesta4', respuesta4Data, 'Pregunta 4');
    </script>
</x-app-layout> 