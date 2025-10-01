<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Encuesta Konkret</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .question-card {
            transition: all 0.3s ease;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .question-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .rating-button {
            transition: all 0.3s ease;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
        }

        .rating-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            transition: left 0.5s;
        }

        .rating-button:hover::before {
            left: 100%;
        }

        .rating-button:hover {
            transform: scale(1.15);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .rating-button.selected {
            background: #0E1D49;
            color: white;
            transform: scale(1.15);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .star-rating {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin: 25px 0;
            flex-wrap: wrap;
        }

        .star {
            font-size: 2.2rem;
            color: #e5e7eb;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 4px;
            border-radius: 50%;
            position: relative;
        }

        .star:hover {
            transform: scale(1.2) rotate(10deg);
            color: #f59e0b;
            text-shadow: 0 0 20px rgba(245, 158, 11, 0.5);
        }

        .star.selected {
            color: #fbbf24;
            transform: scale(1.1);
            text-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
        }

        .star.hovered {
            color: #f59e0b;
            transform: scale(1.15);
        }

        /* Estilos para botones mejorados */
        .btn-primary {
            background: linear-gradient(135deg, #003ED8 0%, #9C9B9B 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 62, 216, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(0, 62, 216, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }


        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px rgba(16, 185, 129, 0.4);
        }

        /* Responsive para móvil */
        @media (max-width: 640px) {
            .star {
                font-size: 1.5rem;
            }

            .star-rating {
                gap: 2px;
                margin: 15px 0;
            }

            .question-card {
                padding: 1.5rem;
            }

            .text-2xl {
                font-size: 1.5rem;
            }

            .text-lg {
                font-size: 1rem;
            }

            .btn-primary,
            .btn-success {
                padding: 10px 24px;
                font-size: 1rem;
            }

            .rating-button {
                width: 24px;
                height: 24px;
            }
        }

        @media (max-width: 480px) {
            .star {
                font-size: 1.2rem;
            }

            .star-rating {
                gap: 1px;
                margin: 10px 0;
            }

            .question-card {
                padding: 1rem;
            }

            .text-2xl {
                font-size: 1.25rem;
            }

            .text-lg {
                font-size: 0.9rem;
            }

            .text-sm {
                font-size: 0.75rem;
            }

            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }

        @media (max-width: 360px) {
            .star {
                font-size: 1rem;
            }

            .star-rating {
                gap: 0.5px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="bg-[#0E1D49] min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-2xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/Recurso_4.png') }}" alt="Logo Konkret" class="w-12 h-12 object-contain">
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Encuesta de Satisfacción</h1>
                <p class="text-white text-xl">Esta encuesta ya no está disponible</p>
            </div>

            <!-- Error Message -->
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-6">
                    <i class="fas fa-times-circle text-6xl text-red-400 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Encuesta no disponible</h3>
                    <p class="text-gray-600 text-lg leading-relaxed">
                        {{ $mensaje ?? 'Lo sentimos, esta encuesta ya no está disponible.' }}
                    </p>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-info-circle text-red-600 mr-2"></i>
                        <span class="font-semibold text-red-800">Información adicional</span>
                    </div>
                    <ul class="text-red-700 text-sm text-left space-y-1">
                        <li>• La encuesta puede haber sido cancelada</li>
                        <li>• Puede haber ocurrido un error técnico</li>
                        <li>• El período de respuesta puede haber finalizado</li>
                        <li>• Contacta al administrador para más información</li>
                    </ul>
                </div>

                <div class="space-y-4">
                
                    <button onclick="window.close()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-lg transition duration-200">
                        <i class="fas fa-times mr-2"></i>
                        Cerrar Ventana
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8">
                <p class="text-white text-sm">
                    Si el problema persiste, contacta a soporte técnico
                </p>
            </div>
        </div>
    </div>
</body>
</html>
