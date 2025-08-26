<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat de WhatsApp - Konkret</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-blue-600 text-white p-4">
            <div class="container mx-auto">
                <h1 class="text-2xl font-bold">💬 Chat de WhatsApp</h1>
                <p class="text-blue-100">Enviar mensajes y registrar respuestas</p>
            </div>
        </div>

        <div class="container mx-auto p-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Panel de envío de mensajes -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">📤 Enviar Mensaje</h2>
                    
                    <form id="chatForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Número de WhatsApp
                            </label>
                            <input type="text" 
                                   id="to" 
                                   name="to" 
                                   placeholder="+529961100930" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre del destinatario
                            </label>
                            <input type="text" 
                                   id="nombre" 
                                   name="nombre" 
                                   placeholder="Jose" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Código de referencia
                            </label>
                            <input type="text" 
                                   id="codigo" 
                                   name="codigo" 
                                   placeholder="CHAT001" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Mensaje
                            </label>
                            <textarea id="mensaje" 
                                      name="mensaje" 
                                      rows="4" 
                                      placeholder="Escribe tu mensaje aquí..." 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      required></textarea>
                        </div>

                        <button type="submit" 
                                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            📤 Enviar Mensaje
                        </button>
                    </form>

                    <!-- Resultado del envío -->
                    <div id="resultado" class="mt-4 hidden">
                        <div id="successMessage" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <span id="successText"></span>
                        </div>
                        <div id="errorMessage" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <span id="errorText"></span>
                        </div>
                    </div>
                </div>

                <!-- Panel de respuestas recibidas -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">📥 Respuestas Recibidas</h2>
                        <button onclick="cargarRespuestas()" 
                                class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                            🔄 Actualizar
                        </button>
                    </div>

                    <div id="respuestas" class="space-y-3 max-h-96 overflow-y-auto">
                        <div class="text-gray-500 text-center py-8">
                            Haz clic en "Actualizar" para cargar las respuestas
                        </div>
                    </div>
                </div>

                <!-- Panel de historial y logs -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">📋 Historial de Envíos</h2>
                        <button onclick="cargarHistorial()" 
                                class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                            🔄 Actualizar
                        </button>
                    </div>

                    <div id="historial" class="space-y-3 max-h-96 overflow-y-auto">
                        <div class="text-gray-500 text-center py-8">
                            Haz clic en "Actualizar" para cargar el historial
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del webhook -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">🔗 Configuración del Webhook</h3>
                <p class="text-gray-600 mb-3">
                    Para recibir respuestas automáticamente, configura este webhook en tu cuenta de Twilio:
                </p>
                <div class="bg-gray-100 p-3 rounded-md">
                    <code class="text-sm text-gray-800">
                        {{ url('/chat/webhook-respuesta') }}
                    </code>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Este webhook registrará automáticamente todas las respuestas recibidas de WhatsApp.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Enviar mensaje
        document.getElementById('chatForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await axios.post('/chat/enviar', data);
                
                if (response.data.success) {
                    mostrarResultado('success', response.data.message);
                    this.reset();
                    cargarHistorial(); // Actualizar historial
                } else {
                    mostrarResultado('error', response.data.message);
                }
            } catch (error) {
                mostrarResultado('error', 'Error al enviar el mensaje: ' + (error.response?.data?.message || error.message));
            }
        });

        // Mostrar resultado
        function mostrarResultado(tipo, mensaje) {
            const resultado = document.getElementById('resultado');
            const successMessage = document.getElementById('successMessage');
            const errorMessage = document.getElementById('errorMessage');
            const successText = document.getElementById('successText');
            const errorText = document.getElementById('errorText');

            resultado.classList.remove('hidden');
            
            if (tipo === 'success') {
                successText.textContent = mensaje;
                successMessage.classList.remove('hidden');
                errorMessage.classList.add('hidden');
            } else {
                errorText.textContent = mensaje;
                errorMessage.classList.remove('hidden');
                successMessage.classList.add('hidden');
            }

            // Ocultar después de 5 segundos
            setTimeout(() => {
                resultado.classList.add('hidden');
            }, 5000);
        }

        // Cargar historial de envíos
        async function cargarHistorial() {
            try {
                const response = await axios.get('/chat/historial');
                const historial = document.getElementById('historial');
                
                if (response.data.success && response.data.data.logs.length > 0) {
                    historial.innerHTML = response.data.data.logs.map(log => 
                        `<div class="bg-gray-50 p-3 rounded border-l-4 border-blue-500">
                            <div class="text-sm text-gray-600">${log}</div>
                        </div>`
                    ).join('');
                } else {
                    historial.innerHTML = '<div class="text-gray-500 text-center py-8">No hay mensajes en el historial</div>';
                }
            } catch (error) {
                document.getElementById('historial').innerHTML = 
                    '<div class="text-red-500 text-center py-8">Error al cargar el historial</div>';
            }
        }

        // Cargar respuestas recibidas
        async function cargarRespuestas() {
            try {
                const response = await axios.get('/chat/respuestas');
                const respuestas = document.getElementById('respuestas');
                
                if (response.data.success && response.data.data.data.length > 0) {
                    respuestas.innerHTML = response.data.data.data.map(respuesta => 
                        `<div class="bg-green-50 p-3 rounded border-l-4 border-green-500">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-green-800">${respuesta.formatted_from_number}</span>
                                <span class="text-xs text-green-600">${respuesta.formatted_date}</span>
                            </div>
                            <div class="text-sm text-green-700">${respuesta.body}</div>
                        </div>`
                    ).join('');
                } else {
                    respuestas.innerHTML = '<div class="text-gray-500 text-center py-8">No hay respuestas recibidas</div>';
                }
            } catch (error) {
                document.getElementById('respuestas').innerHTML = 
                    '<div class="text-red-500 text-center py-8">Error al cargar las respuestas</div>';
            }
        }

        // Cargar datos al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            cargarHistorial();
            cargarRespuestas();
        });
    </script>
</body>
</html>
