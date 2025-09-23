<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Encuesta Konkret</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="gradient-bg min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center shadow-lg">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Error</h1>
                <p class="text-red-100 text-xl">No se pudo cargar la encuesta</p>
            </div>

            <!-- Error Message -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-6">
                    <i class="fas fa-times-circle text-6xl text-red-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Algo salió mal</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        {{ $mensaje }}
                    </p>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        <span class="font-semibold text-red-800">Posibles causas</span>
                    </div>
                    <ul class="text-red-700 text-sm text-left space-y-1">
                        <li>• El enlace de la encuesta ha expirado</li>
                        <li>• La encuesta ya fue completada anteriormente</li>
                        <li>• El enlace no es válido o está malformado</li>
                        <li>• Problemas temporales del servidor</li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <button onclick="window.location.reload()" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200 mr-4">
                        <i class="fas fa-redo mr-2"></i>
                        Intentar Nuevamente
                    </button>
                    
                    <button onclick="window.close()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar Ventana
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-red-100 text-sm">
                    Si el problema persiste, contacta a soporte técnico
                </p>
            </div>
        </div>
    </div>
</body>
</html>
