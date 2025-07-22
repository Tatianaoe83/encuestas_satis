<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Visualización de Resultados') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('resultados.detalle') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Ver Detalle
                </a>
                <a href="{{ route('resultados.exportar') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Exportar CSV
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de estadísticas generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-2xl font-bold text-blue-600">{{ $totalEnvios }}</div>
                        <div class="text-sm text-gray-600">Total Envíos</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-2xl font-bold text-green-600">{{ $enviosEnviados }}</div>
                        <div class="text-sm text-gray-600">Enviados</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-2xl font-bold text-purple-600">{{ $enviosRespondidos }}</div>
                        <div class="text-sm text-gray-600">Respondidos</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-2xl font-bold text-yellow-600">{{ $enviosPendientes }}</div>
                        <div class="text-sm text-gray-600">Pendientes</div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-2xl font-bold text-red-600">{{ $tasaRespuesta }}%</div>
                        <div class="text-sm text-gray-600">Tasa de Respuesta</div>
                    </div>
                </div>
            </div>

            <!-- Gráficas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Gráfica de dona - Envíos por estado -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Envíos por Estado</h3>
                        <canvas id="chartEstados" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfica de barras - Top asesores -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Asesores Comerciales</h3>
                        <canvas id="chartAsesores" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfica de línea - Envíos por mes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Envíos por Mes</h3>
                        <canvas id="chartMensual" width="400" height="300"></canvas>
                    </div>
                </div>

                <!-- Gráfica de barras - Envíos por día de la semana -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Envíos por Día de la Semana</h3>
                        <canvas id="chartDias" width="400" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráficas de respuestas por pregunta -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-6">Análisis de Respuestas por Pregunta</h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Respuestas Pregunta 1</h4>
                            <canvas id="chartRespuesta1" width="400" height="300"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Respuestas Pregunta 2</h4>
                            <canvas id="chartRespuesta2" width="400" height="300"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Respuestas Pregunta 3</h4>
                            <canvas id="chartRespuesta3" width="400" height="300"></canvas>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Respuestas Pregunta 4</h4>
                            <canvas id="chartRespuesta4" width="400" height="300"></canvas>
                        </div>
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

        // Gráfica de dona - Envíos por estado
        new Chart(document.getElementById('chartEstados'), {
            type: 'doughnut',
            data: {
                labels: estadosData.map(item => item.estado.charAt(0).toUpperCase() + item.estado.slice(1)),
                datasets: [{
                    data: estadosData.map(item => item.total),
                    backgroundColor: colors.slice(0, estadosData.length),
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Gráfica de barras - Top asesores
        new Chart(document.getElementById('chartAsesores'), {
            type: 'bar',
            data: {
                labels: asesoresData.map(item => item.asesor_comercial),
                datasets: [{
                    label: 'Total Envíos',
                    data: asesoresData.map(item => item.total_envios),
                    backgroundColor: '#3B82F6',
                    borderColor: '#2563EB',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Gráfica de línea - Envíos por mes
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

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
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Gráfica de barras - Envíos por día de la semana
        new Chart(document.getElementById('chartDias'), {
            type: 'bar',
            data: {
                labels: diasData.map(item => diasSemana[item.dia_semana - 1]),
                datasets: [{
                    label: 'Envíos',
                    data: diasData.map(item => item.total),
                    backgroundColor: '#F59E0B',
                    borderColor: '#D97706',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Gráficas de respuestas por pregunta
        function crearGraficaRespuesta(elementId, data, titulo) {
            if (data.length === 0) {
                document.getElementById(elementId).parentElement.innerHTML = '<p class="text-gray-500 text-center py-8">No hay datos disponibles</p>';
                return;
            }

            new Chart(document.getElementById(elementId), {
                type: 'bar',
                data: {
                    labels: data.map(item => item.respuesta_1 || item.respuesta_2 || item.respuesta_3 || item.respuesta_4),
                    datasets: [{
                        label: 'Respuestas',
                        data: data.map(item => item.total),
                        backgroundColor: '#8B5CF6',
                        borderColor: '#7C3AED',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
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