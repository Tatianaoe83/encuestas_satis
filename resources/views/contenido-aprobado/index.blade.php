<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Contenido Aprobado') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Panel de estadísticas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Estadísticas de Timers</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4" id="estadisticas-container">
                        <div class="bg-blue-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600" id="timers-activos">-</div>
                            <div class="text-sm text-blue-800">Timers Activos</div>
                        </div>
                        <div class="bg-yellow-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600" id="timers-expirados">-</div>
                            <div class="text-sm text-yellow-800">Timers Expirados</div>
                        </div>
                        <div class="bg-green-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-green-600" id="respondidos">-</div>
                            <div class="text-sm text-green-800">Respondidos</div>
                        </div>
                        <div class="bg-red-100 p-4 rounded-lg">
                            <div class="text-2xl font-bold text-red-600" id="cancelados">-</div>
                            <div class="text-sm text-red-800">Cancelados</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de envío de contenido aprobado -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Enviar Contenido Aprobado</h3>
                    
                    <form id="form-enviar-contenido" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="envio_id" class="block text-sm font-medium text-gray-700">ID de Envío</label>
                                <input type="number" id="envio_id" name="envio_id" required
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label for="tiempo_espera" class="block text-sm font-medium text-gray-700">Tiempo de Espera (minutos)</label>
                                <input type="number" id="tiempo_espera" name="tiempo_espera_minutos" value="30" min="1" max="1440"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enviar Contenido Aprobado
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Panel de timers activos -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Timers Activos</h3>
                        <button id="btn-refrescar-timers" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Refrescar
                        </button>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID Envío
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Número
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tiempo Restante
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="timers-activos-tbody">
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        Cargando timers activos...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel de acciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Acciones del Sistema</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <button id="btn-verificar-timers" 
                                    class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                Verificar Timers Expirados
                            </button>
                            <span class="ml-2 text-sm text-gray-600">Verifica y cancela timers expirados manualmente</span>
                        </div>
                        
                        <div>
                            <button id="btn-ejecutar-comando" 
                                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Ejecutar Comando Artisan
                            </button>
                            <span class="ml-2 text-sm text-gray-600">Ejecuta el comando timers:verificar</span>
                        </div>
                        
                        <div>
                            <button id="btn-verificar-cron-interno" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Verificar Cron Interno
                            </button>
                            <span class="ml-2 text-sm text-gray-600">Verifica el estado del cron interno automático</span>
                        </div>
                        
                        <div>
                            <button id="btn-forzar-cron-interno" 
                                    class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">
                                Forzar Cron Interno
                            </button>
                            <span class="ml-2 text-sm text-gray-600">Fuerza la ejecución inmediata del cron interno</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación -->
    <div id="modal-confirmacion" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
                <h3 class="text-lg font-semibold mb-4" id="modal-titulo">Confirmar Acción</h3>
                <p class="text-gray-600 mb-6" id="modal-mensaje">¿Estás seguro de que deseas realizar esta acción?</p>
                <div class="flex justify-end space-x-3">
                    <button id="btn-cancelar-modal" 
                            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button id="btn-confirmar-modal" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        let envioIdParaCancelar = null;

        // Función para mostrar notificaciones
        function mostrarNotificacion(mensaje, tipo = 'success') {
            const alertClass = tipo === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            
            const alert = document.createElement('div');
            alert.className = `fixed top-4 right-4 p-4 border rounded-lg ${alertClass} z-50`;
            alert.textContent = mensaje;
            
            document.body.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Función para cargar estadísticas
        async function cargarEstadisticas() {
            try {
                const response = await fetch('{{ route("contenido-aprobado.estadisticas") }}');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('timers-activos').textContent = data.data.total_timers_activos;
                    document.getElementById('timers-expirados').textContent = data.data.total_timers_expirados;
                    document.getElementById('respondidos').textContent = data.data.total_respondidos;
                    document.getElementById('cancelados').textContent = data.data.total_cancelados;
                }
            } catch (error) {
                console.error('Error cargando estadísticas:', error);
            }
        }

        // Función para cargar timers activos
        async function cargarTimersActivos() {
            try {
                const response = await fetch('{{ route("contenido-aprobado.timers-activos") }}');
                const data = await response.json();
                
                const tbody = document.getElementById('timers-activos-tbody');
                
                if (data.success && data.data.timers_activos.length > 0) {
                    tbody.innerHTML = data.data.timers_activos.map(timer => `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                ${timer.envio_id}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${timer.cliente}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${timer.numero}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="font-semibold ${timer.tiempo_restante_minutos < 5 ? 'text-red-600' : 'text-yellow-600'}">
                                    ${timer.tiempo_restante_minutos} min
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    ${timer.estado_timer}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="cancelarTimer(${timer.envio_id})" 
                                        class="text-red-600 hover:text-red-900">
                                    Cancelar
                                </button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                No hay timers activos
                            </td>
                        </tr>
                    `;
                }
            } catch (error) {
                console.error('Error cargando timers activos:', error);
                document.getElementById('timers-activos-tbody').innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-red-500">
                            Error cargando timers activos
                        </td>
                    </tr>
                `;
            }
        }

        // Función para cancelar timer
        function cancelarTimer(envioId) {
            envioIdParaCancelar = envioId;
            
            document.getElementById('modal-titulo').textContent = 'Cancelar Timer';
            document.getElementById('modal-mensaje').textContent = `¿Estás seguro de que deseas cancelar el timer del envío ${envioId}?`;
            document.getElementById('modal-confirmacion').classList.remove('hidden');
        }

        // Función para confirmar cancelación
        async function confirmarCancelacion() {
            if (!envioIdParaCancelar) return;
            
            try {
                const response = await fetch('{{ route("contenido-aprobado.cancelar-timer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        envio_id: envioIdParaCancelar
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    mostrarNotificacion('Timer cancelado exitosamente', 'success');
                    cargarTimersActivos();
                    cargarEstadisticas();
                } else {
                    mostrarNotificacion(data.message || 'Error cancelando timer', 'error');
                }
            } catch (error) {
                console.error('Error cancelando timer:', error);
                mostrarNotificacion('Error cancelando timer', 'error');
            }
            
            document.getElementById('modal-confirmacion').classList.add('hidden');
            envioIdParaCancelar = null;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar datos iniciales
            cargarEstadisticas();
            cargarTimersActivos();
            
            // Form de envío de contenido aprobado
            document.getElementById('form-enviar-contenido').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch('{{ route("contenido-aprobado.enviar") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        mostrarNotificacion('Contenido aprobado enviado exitosamente', 'success');
                        this.reset();
                        cargarEstadisticas();
                        cargarTimersActivos();
                    } else {
                        mostrarNotificacion(data.message || 'Error enviando contenido', 'error');
                    }
                } catch (error) {
                    console.error('Error enviando contenido:', error);
                    mostrarNotificacion('Error enviando contenido', 'error');
                }
            });
            
            // Botón refrescar timers
            document.getElementById('btn-refrescar-timers').addEventListener('click', function() {
                cargarTimersActivos();
                cargarEstadisticas();
            });
            
            // Botón verificar timers
            document.getElementById('btn-verificar-timers').addEventListener('click', async function() {
                try {
                    const response = await fetch('{{ route("contenido-aprobado.verificar-timers") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        mostrarNotificacion(`Verificación completada. ${data.data.timers_cancelados} timers cancelados`, 'success');
                        cargarTimersActivos();
                        cargarEstadisticas();
                    } else {
                        mostrarNotificacion(data.message || 'Error en verificación', 'error');
                    }
                } catch (error) {
                    console.error('Error verificando timers:', error);
                    mostrarNotificacion('Error verificando timers', 'error');
                }
            });
            
            // Botón ejecutar comando
            document.getElementById('btn-ejecutar-comando').addEventListener('click', async function() {
                try {
                    const response = await fetch('/api/ejecutar-comando-timers', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        mostrarNotificacion('Comando ejecutado exitosamente', 'success');
                        cargarTimersActivos();
                        cargarEstadisticas();
                    } else {
                        mostrarNotificacion(data.message || 'Error ejecutando comando', 'error');
                    }
                } catch (error) {
                    console.error('Error ejecutando comando:', error);
                    mostrarNotificacion('Error ejecutando comando', 'error');
                }
            });
            
            // Botón verificar cron interno
            document.getElementById('btn-verificar-cron-interno').addEventListener('click', async function() {
                try {
                    const response = await fetch('{{ route("cron-interno.estado") }}');
                    const data = await response.json();
                    
                    if (data.success) {
                        const estado = data.data;
                        const mensaje = `Estado del Cron Interno:\n` +
                            `• Última ejecución: ${estado.ultima_ejecucion || 'Nunca'}\n` +
                            `• Próxima ejecución: ${estado.proxima_ejecucion}\n` +
                            `• Timers activos: ${estado.timers_activos}\n` +
                            `• Timers expirados: ${estado.timers_expirados}`;
                        
                        mostrarNotificacion(mensaje, 'success');
                    } else {
                        mostrarNotificacion(data.message || 'Error verificando cron interno', 'error');
                    }
                } catch (error) {
                    console.error('Error verificando cron interno:', error);
                    mostrarNotificacion('Error verificando cron interno', 'error');
                }
            });
            
            // Botón forzar cron interno
            document.getElementById('btn-forzar-cron-interno').addEventListener('click', async function() {
                try {
                    const response = await fetch('{{ route("cron-interno.forzar") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        mostrarNotificacion(`Cron forzado ejecutado. ${data.data.timers_cancelados} timers cancelados`, 'success');
                        cargarTimersActivos();
                        cargarEstadisticas();
                    } else {
                        mostrarNotificacion(data.message || 'Error forzando cron interno', 'error');
                    }
                } catch (error) {
                    console.error('Error forzando cron interno:', error);
                    mostrarNotificacion('Error forzando cron interno', 'error');
                }
            });
            
            // Modal de confirmación
            document.getElementById('btn-cancelar-modal').addEventListener('click', function() {
                document.getElementById('modal-confirmacion').classList.add('hidden');
                envioIdParaCancelar = null;
            });
            
            document.getElementById('btn-confirmar-modal').addEventListener('click', confirmarCancelacion);
            
            // Auto-refresh cada 30 segundos
            setInterval(() => {
                cargarTimersActivos();
                cargarEstadisticas();
            }, 30000);
        });
    </script>
</x-app-layout>
