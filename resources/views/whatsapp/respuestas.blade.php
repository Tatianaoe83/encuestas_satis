<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuestas de Encuestas - EncuestasPro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-900">EncuestasPro</h1>
                        <span class="ml-2 text-sm text-gray-500">Sistema de Encuestas</span>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Respuestas en tiempo real</span>
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Respuestas</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="total-respuestas">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Hoy</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="respuestas-hoy">0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Promedio</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="promedio-satisfaccion">0.0</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Tasa Respuesta</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="tasa-respuesta">0%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Satisfaction Chart -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Distribución de Satisfacción</h3>
                    <canvas id="satisfactionChart" class="w-full" height="300"></canvas>
                </div>

                <!-- Response Trend -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tendencia de Respuestas</h3>
                    <canvas id="trendChart" class="w-full" height="300"></canvas>
                </div>
            </div>

            <!-- Recent Responses Table -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Respuestas Recientes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Calificación</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razón</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo Obra</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sugerencias</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="respuestas-table">
                                <!-- Las respuestas se cargarán dinámicamente aquí -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Datos de ejemplo para las gráficas
        let satisfactionChart, trendChart;
        let respuestas = [];

        // Inicializar gráficas
        function initCharts() {
            // Gráfica de satisfacción
            const satisfactionCtx = document.getElementById('satisfactionChart').getContext('2d');
            satisfactionChart = new Chart(satisfactionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Muy Satisfecho (9-10)', 'Satisfecho (7-8)', 'Neutral (5-6)', 'Insatisfecho (0-4)'],
                    datasets: [{
                        data: [0, 0, 0, 0],
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Gráfica de tendencia
            const trendCtx = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Calificación Promedio',
                        data: [],
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10
                        }
                    }
                }
            });
        }

        // Función para actualizar estadísticas
        function updateStats() {
            const total = respuestas.length;
            const hoy = respuestas.filter(r => {
                const fecha = new Date(r.fecha || Date.now());
                const hoy = new Date();
                return fecha.toDateString() === hoy.toDateString();
            }).length;
            
            const promedio = total > 0 ? (respuestas.reduce((sum, r) => sum + (parseInt(r.respuesta_1) || 0), 0) / total).toFixed(1) : '0.0';
            const tasa = total > 0 ? Math.round((total / (total + 50)) * 100) : 0; // Simulación

            document.getElementById('total-respuestas').textContent = total;
            document.getElementById('respuestas-hoy').textContent = hoy;
            document.getElementById('promedio-satisfaccion').textContent = promedio;
            document.getElementById('tasa-respuesta').textContent = tasa + '%';
        }

        // Función para actualizar gráficas
        function updateCharts() {
            if (respuestas.length === 0) return;

            // Actualizar gráfica de satisfacción
            const calificaciones = respuestas.map(r => parseInt(r.respuesta_1) || 0);
            const muySatisfecho = calificaciones.filter(c => c >= 9).length;
            const satisfecho = calificaciones.filter(c => c >= 7 && c < 9).length;
            const neutral = calificaciones.filter(c => c >= 5 && c < 7).length;
            const insatisfecho = calificaciones.filter(c => c < 5).length;

            satisfactionChart.data.datasets[0].data = [muySatisfecho, satisfecho, neutral, insatisfecho];
            satisfactionChart.update();

            // Actualizar gráfica de tendencia
            const fechas = [...new Set(respuestas.map(r => r.fecha || new Date().toDateString()))].sort();
            const promedios = fechas.map(fecha => {
                const respuestasFecha = respuestas.filter(r => (r.fecha || new Date().toDateString()) === fecha);
                return respuestasFecha.length > 0 ? 
                    respuestasFecha.reduce((sum, r) => sum + (parseInt(r.respuesta_1) || 0), 0) / respuestasFecha.length : 0;
            });

            trendChart.data.labels = fechas;
            trendChart.data.datasets[0].data = promedios;
            trendChart.update();
        }

        // Función para actualizar tabla
        function updateTable() {
            const tbody = document.getElementById('respuestas-table');
            tbody.innerHTML = '';

            respuestas.slice(0, 10).forEach(respuesta => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${respuesta.cliente || 'Cliente'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                            (parseInt(respuesta.respuesta_1) >= 9) ? 'bg-green-100 text-green-800' :
                            (parseInt(respuesta.respuesta_1) >= 7) ? 'bg-blue-100 text-blue-800' :
                            (parseInt(respuesta.respuesta_1) >= 5) ? 'bg-yellow-100 text-yellow-800' :
                            'bg-red-100 text-red-800'
                        }">
                            ${respuesta.respuesta_1 || 'N/A'}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                        ${respuesta.respuesta_2 || 'N/A'}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        ${respuesta.respuesta_3 || 'N/A'}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                        ${respuesta.respuesta_4 || 'N/A'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${new Date(respuesta.fecha || Date.now()).toLocaleDateString('es-ES')}
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Función para simular recepción de datos
        function simularRecepcionDatos() {
            // Simular datos de ejemplo
            const datosEjemplo = {
                cliente: 'Cliente Ejemplo ' + (respuestas.length + 1),
                respuesta_1: Math.floor(Math.random() * 11),
                respuesta_2: 'Calidad del producto',
                respuesta_3: 'Edificación',
                respuesta_4: 'Mejorar tiempos de entrega',
                fecha: new Date().toISOString()
            };

            respuestas.unshift(datosEjemplo);
            updateStats();
            updateCharts();
            updateTable();
        }

        // Inicializar cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            initCharts();
            loadRespuestas();
            
            // Actualizar datos cada 30 segundos
            setInterval(loadRespuestas, 30000);
        });

        // Función para cargar respuestas desde la API
        function loadRespuestas() {
            fetch('/api/respuestas')
                .then(response => response.json())
                .then(data => {
                    respuestas = data;
                    updateStats();
                    updateCharts();
                    updateTable();
                })
                .catch(error => {
                    console.error('Error al cargar respuestas:', error);
                    // Si hay error, usar datos simulados para demostración
                    simularRecepcionDatos();
                });
        }
    </script>
</body>
</html>
