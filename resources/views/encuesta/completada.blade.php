<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Encuesta Completada - Konkret</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto';
        }

        .gradient-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="bg-[#0E1D49] min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-24 h-24 mx-auto mb-6 flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/Recurso_4.png') }}" alt="Logo Konkret" class="w-12 h-12 object-contain">
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">¡Encuesta Completada!</h1>
                <p class="text-green-100 text-xl">Gracias por tu tiempo y valiosa opinión</p>
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

                <div class="bg-[#0E1D49] border border-[#0E1D49] rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-shield-alt text-white mr-2"></i>
                        <span class="font-semibold text-white">Información Segura</span>
                    </div>
                    <p class="text-white text-sm">
                        Tus respuestas son confidenciales y se utilizarán únicamente para mejorar nuestros servicios.
                    </p>
                </div>

                <div class="space-y-4">
                    <button onclick="window.location.href='/'"
                        class="bg-red-600 hover:bg-red-700 hover:scale-105 text-white font-bold py-3 px-8 rounded-lg transition duration-200 mr-4">
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