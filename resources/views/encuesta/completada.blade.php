<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Completada - Konkret</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="gradient-bg min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center shadow-lg">
                    <i class="fas fa-check-circle text-4xl text-green-600"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">¡Encuesta Completada!</h1>
                <p class="text-green-100 text-xl">Gracias por tu tiempo y valiosa opinión</p>
            </div>

            <!-- Cliente Info -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $cliente->nombre_completo }}</h2>
                        <p class="text-gray-600">{{ $cliente->razon_social }}</p>
                        <p class="text-sm text-gray-500">{{ $cliente->puesto }}</p>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-6">
                    <i class="fas fa-star text-6xl text-yellow-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">¡Tu opinión es muy importante!</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        Hemos recibido tus respuestas correctamente. Tu feedback nos ayuda a mejorar 
                        continuamente nuestros servicios y brindarte una mejor experiencia.
                    </p>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                        <span class="font-semibold text-green-800">Información Segura</span>
                    </div>
                    <p class="text-green-700 text-sm">
                        Tus respuestas son confidenciales y se utilizarán únicamente para mejorar nuestros servicios.
                    </p>
                </div>

                <div class="space-y-4">
                    <button onclick="window.close()" 
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 mr-4">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar Ventana
                    </button>
                    
                    
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-green-100 text-sm">
                    © {{ date('Y') }} Konkret - Todos los derechos reservados
                </p>
            </div>
        </div>
    </div>
</body>
</html>
