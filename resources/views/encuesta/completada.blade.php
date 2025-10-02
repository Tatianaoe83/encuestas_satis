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

        /* Estilos responsive para móvil */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .text-4xl {
                font-size: 2rem;
            }

            .text-xl {
                font-size: 1.125rem;
            }

            .text-2xl {
                font-size: 1.5rem;
            }

            .text-lg {
                font-size: 1rem;
            }

            .w-24 {
                width: 5rem;
            }

            .h-24 {
                height: 5rem;
            }

            .w-12 {
                width: 2.5rem;
            }

            .h-12 {
                height: 2.5rem;
            }

            .text-6xl {
                font-size: 3rem;
            }

            .py-3 {
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .px-8 {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .text-4xl {
                font-size: 1.75rem;
            }

            .text-xl {
                font-size: 1rem;
            }

            .text-2xl {
                font-size: 1.25rem;
            }

            .text-lg {
                font-size: 0.9rem;
            }

            .w-24 {
                width: 4rem;
            }

            .h-24 {
                height: 4rem;
            }

            .w-12 {
                width: 2rem;
            }

            .h-12 {
                height: 2rem;
            }

            .text-6xl {
                font-size: 2.5rem;
            }

            .py-3 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }

            .px-8 {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .p-8 {
                padding: 1.5rem;
            }

            .p-6 {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .text-4xl {
                font-size: 1.5rem;
            }

            .text-xl {
                font-size: 0.9rem;
            }

            .text-2xl {
                font-size: 1.125rem;
            }

            .text-lg {
                font-size: 0.85rem;
            }

            .text-sm {
                font-size: 0.75rem;
            }

            .w-24 {
                width: 3.5rem;
            }

            .h-24 {
                height: 3.5rem;
            }

            .w-12 {
                width: 1.75rem;
            }

            .h-12 {
                height: 1.75rem;
            }

            .text-6xl {
                font-size: 2rem;
            }

            .py-3 {
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }

            .px-8 {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .p-8 {
                padding: 1rem;
            }

            .p-6 {
                padding: 0.75rem;
            }

            .mb-6 {
                margin-bottom: 1rem;
            }

            .mb-4 {
                margin-bottom: 0.75rem;
            }

            .mb-2 {
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 360px) {
            .container {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            .text-4xl {
                font-size: 1.25rem;
            }

            .text-xl {
                font-size: 0.85rem;
            }

            .text-2xl {
                font-size: 1rem;
            }

            .text-lg {
                font-size: 0.8rem;
            }

            .text-sm {
                font-size: 0.7rem;
            }

            .w-24 {
                width: 3rem;
            }

            .h-24 {
                height: 3rem;
            }

            .w-12 {
                width: 1.5rem;
            }

            .h-12 {
                height: 1.5rem;
            }

            .text-6xl {
                font-size: 1.75rem;
            }

            .py-3 {
                padding-top: 0.375rem;
                padding-bottom: 0.375rem;
            }

            .px-8 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .p-8 {
                padding: 0.75rem;
            }

            .p-6 {
                padding: 0.5rem;
            }

            .mb-6 {
                margin-bottom: 0.75rem;
            }

            .mb-4 {
                margin-bottom: 0.5rem;
            }

            .mb-2 {
                margin-bottom: 0.375rem;
            }
        }

        @media (max-width: 320px) {
            .container {
                padding-left: 0.125rem;
                padding-right: 0.125rem;
            }

            .text-4xl {
                font-size: 1.125rem;
            }

            .text-xl {
                font-size: 0.8rem;
            }

            .text-2xl {
                font-size: 0.9rem;
            }

            .text-lg {
                font-size: 0.75rem;
            }

            .text-sm {
                font-size: 0.65rem;
            }

            .w-24 {
                width: 2.5rem;
            }

            .h-24 {
                height: 2.5rem;
            }

            .w-12 {
                width: 1.25rem;
            }

            .h-12 {
                height: 1.25rem;
            }

            .text-6xl {
                font-size: 1.5rem;
            }

            .py-3 {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }

            .px-8 {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
            }

            .p-8 {
                padding: 0.5rem;
            }

            .p-6 {
                padding: 0.375rem;
            }

            .mb-6 {
                margin-bottom: 0.5rem;
            }

            .mb-4 {
                margin-bottom: 0.375rem;
            }

            .mb-2 {
                margin-bottom: 0.25rem;
            }
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
                    <button onclick="window.close()"
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