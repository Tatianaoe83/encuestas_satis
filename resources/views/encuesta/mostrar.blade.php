<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Encuesta de Satisfacción - Konkret</title>
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
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .question-card {
                padding: 1.5rem;
                margin: 0.5rem;
            }

            .text-3xl {
                font-size: 1.75rem;
            }

            .text-xl {
                font-size: 1.125rem;
            }

            .text-lg {
                font-size: 1rem;
            }

            .star-rating {
                gap: 6px;
                margin: 20px 0;
                justify-content: center;
                flex-wrap: wrap;
            }

            .star {
                font-size: 2rem;
                padding: 8px;
                min-width: 44px;
                min-height: 44px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .btn-primary,
            .btn-success {
                padding: 12px 28px;
                font-size: 1rem;
                min-height: 48px;
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .rating-button {
                width: 80px;
                height: 80px;
                font-size: 1.5rem;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            #btn-si, #btn-no {
                width: 100px;
                height: 100px;
                font-size: 1.8rem;
            }

            #btn-si div, #btn-no div {
                font-size: 0.9rem;
                margin-top: 4px;
            }

            /* Asegurar que los iconos se muestren correctamente */
            .fas, .far, .fab {
                display: inline-block;
                font-style: normal;
                font-variant: normal;
                text-rendering: auto;
                line-height: 1;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }

            .question-card {
                padding: 1.25rem;
                margin: 0.25rem;
            }

            .text-3xl {
                font-size: 1.5rem;
            }

            .text-xl {
                font-size: 1rem;
            }

            .text-lg {
                font-size: 0.9rem;
            }

            .star-rating {
                gap: 4px;
                margin: 15px 0;
            }

            .star {
                font-size: 1.8rem;
                padding: 6px;
                min-width: 40px;
                min-height: 40px;
            }

            .btn-primary,
            .btn-success {
                padding: 10px 24px;
                font-size: 0.9rem;
                min-height: 44px;
                max-width: 280px;
            }

            .rating-button {
                width: 70px;
                height: 70px;
                font-size: 1.3rem;
            }

            #btn-si, #btn-no {
                width: 90px;
                height: 90px;
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .question-card {
                padding: 1rem;
                margin: 0.25rem;
            }

            .text-3xl {
                font-size: 1.25rem;
            }

            .text-xl {
                font-size: 0.9rem;
            }

            .text-lg {
                font-size: 0.85rem;
            }

            .text-sm {
                font-size: 0.75rem;
            }

            .star-rating {
                gap: 3px;
                margin: 12px 0;
            }

            .star {
                font-size: 1.6rem;
                padding: 4px;
                min-width: 36px;
                min-height: 36px;
            }

            .btn-primary,
            .btn-success {
                padding: 8px 20px;
                font-size: 0.85rem;
                min-height: 40px;
                max-width: 260px;
            }

            .rating-button {
                width: 60px;
                height: 60px;
                font-size: 1.1rem;
            }

            #btn-si, #btn-no {
                width: 80px;
                height: 80px;
                font-size: 1.4rem;
            }

            #btn-si div, #btn-no div {
                font-size: 0.8rem;
                margin-top: 2px;
            }
        }

        @media (max-width: 360px) {
            .container {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            .question-card {
                padding: 0.75rem;
                margin: 0.125rem;
            }

            .text-3xl {
                font-size: 1.1rem;
            }

            .text-xl {
                font-size: 0.85rem;
            }

            .text-lg {
                font-size: 0.8rem;
            }

            .star-rating {
                gap: 2px;
                margin: 10px 0;
            }

            .star {
                font-size: 1.4rem;
                padding: 3px;
                min-width: 32px;
                min-height: 32px;
            }

            .rating-button {
                width: 50px;
                height: 50px;
                font-size: 1rem;
            }

            #btn-si, #btn-no {
                width: 70px;
                height: 70px;
                font-size: 1.2rem;
            }

            #btn-si div, #btn-no div {
                font-size: 0.7rem;
                margin-top: 1px;
            }

            .btn-primary,
            .btn-success {
                padding: 6px 16px;
                font-size: 0.8rem;
                min-height: 36px;
                max-width: 240px;
            }
        }

        /* Estilos para dispositivos con pantalla muy pequeña */
        @media (max-width: 320px) {
            .container {
                padding-left: 0.125rem;
                padding-right: 0.125rem;
            }

            .question-card {
                padding: 0.5rem;
                margin: 0.125rem;
            }

            .text-3xl {
                font-size: 1rem;
            }

            .text-xl {
                font-size: 0.8rem;
            }

            .text-lg {
                font-size: 0.75rem;
            }

            .star-rating {
                gap: 1px;
                margin: 8px 0;
            }

            .star {
                font-size: 1.2rem;
                min-width: 28px;
                min-height: 28px;
                padding: 2px;
            }

            .rating-button {
                width: 45px;
                height: 45px;
                font-size: 0.9rem;
            }

            #btn-si, #btn-no {
                width: 65px;
                height: 65px;
                font-size: 1.1rem;
            }

            #btn-si div, #btn-no div {
                font-size: 0.65rem;
                margin-top: 1px;
            }

            .btn-primary, .btn-success {
                padding: 5px 12px;
                font-size: 0.75rem;
                min-height: 32px;
                max-width: 220px;
            }
        }

        /* Mejorar la experiencia táctil para todos los dispositivos móviles */
        @media (max-width: 768px) {
            .star, .rating-button, .btn-primary, .btn-success {
                touch-action: manipulation;
                -webkit-tap-highlight-color: transparent;
            }

            .star:hover {
                transform: scale(1.1);
            }

            .rating-button:hover {
                transform: scale(1.05);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="bg-[#0E1D49] min-h-screen py-8">
        <div class="container mx-auto px-4 max-w-4xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="bg-white rounded-full w-20 h-20 mx-auto mb-4 flex items-center justify-center shadow-lg">
                    <img src="{{ asset('images/Recurso_4.png') }}" alt="Logo Konkret" class="w-12 h-12 object-contain">
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Encuesta de Satisfacción</h1>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-700">Progreso de la encuesta</span>
                    <span class="text-sm font-medium text-gray-700" id="progress-text">0%</span>
                </div>
                <div class="w-full bg-[#9C9B9B] rounded-full h-2">
                    <div id="progress-bar"
                        class="h-2 rounded-full transition-all duration-500 bg-gradient-to-r from-[#0E1D49] to-[#003ED8]"
                        style="width: 0%">
                    </div>
                </div>
            </div>

            <!-- Question Card -->
            <div class="question-card bg-white rounded-lg shadow-lg p-8" id="question-card">
                <div id="question-content">
                    <!-- El contenido se carga dinámicamente -->
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loading" class="hidden text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <p class="text-white mt-2">Procesando respuesta...</p>
            </div>

            <!-- Success Message -->
            <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span id="success-text">¡Respuesta guardada correctamente!</span>
                </div>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <span id="error-text">Error al procesar la respuesta.</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        const envioId = '{{$idencrypted}}';
       
        const preguntaActual = '{{ $preguntaActual }}';
        let progresoActual = 0;

        // Mapeo de preguntas para el progreso
        const preguntasMap = {
            '1.1': 1,
            '1.2': 2,
            '1.3': 3,
            '1.4': 4,
            '1.5': 5,
            '2': 6,
            '3': 7,
            'completado': 8
        };

        // Cargar pregunta inicial
        document.addEventListener('DOMContentLoaded', function() {
            cargarPregunta(preguntaActual);
            actualizarProgreso(preguntaActual);
        });

        function cargarPregunta(pregunta) {
            const questionContent = document.getElementById('question-content');

            if (pregunta === 'completado') {
                mostrarEncuestaCompletada();
                return;
            }

            let html = '';

            // Determinar tipo de pregunta
            if (['1.1', '1.2', '1.3', '1.4', '1.5'].includes(pregunta)) {
                html = generarPreguntaEscala(pregunta);
            } else if (pregunta === '2') {
                html = generarPreguntaSiNo();
            } else if (pregunta === '3') {
                html = generarPreguntaAbierta();
            } else {
                html = generarContenidoSimple();
            }

            questionContent.innerHTML = html;

            // Asegurar que el botón esté deshabilitado después de cargar
            setTimeout(() => {
                const submitBtn = document.getElementById('submit-btn');
                if (submitBtn) {
                    submitBtn.disabled = true;
                }

                // Configurar eventos según el tipo de pregunta
                if (['1.1', '1.2', '1.3', '1.4', '1.5'].includes(pregunta)) {
                    configurarEventosEstrellas();
                } else if (pregunta === '2') {
                    configurarEventosSiNo();
                } else if (pregunta === '3') {
                    configurarEventosTextarea();
                } else {
                    configurarEventosTextarea();
                }
            }, 100);
        }

        function generarPreguntaEscala(pregunta) {
            const preguntas = {
                '1.1': {
                    titulo: 'Calidad del producto',
                    pregunta: '¿Cómo calificarías la calidad del producto?'
                },
                '1.2': {
                    titulo: 'Puntualidad de entrega',
                    pregunta: '¿Cómo calificarías la puntualidad de entrega?'
                },
                '1.3': {
                    titulo: 'Trato del asesor comercial',
                    pregunta: '¿Cómo calificarías el trato del asesor comercial?'
                },
                '1.4': {
                    titulo: 'Precio',
                    pregunta: '¿Cómo calificarías el precio del producto?'
                },
                '1.5': {
                    titulo: 'Rapidez en programación',
                    pregunta: '¿Cómo calificarías la rapidez en programación?'
                }
            };

            const infoPregunta = preguntas[pregunta] || preguntas['1.1'];

            return `
                <div class="text-center">
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">${infoPregunta.titulo}</h3>
                        <p class="text-xl text-gray-600 mb-6">${infoPregunta.pregunta}</p>
                        <p class="text-lg text-gray-500">Selecciona de 1 a 10 estrellas</p>
                    </div>
                    
                    <div class="max-w-2xl mx-auto mb-8">
                        <div class="bg-white rounded-xl p-8 border-2 border-gray-200 shadow-lg">
                            <div class="star-rating mb-6" id="star-rating">
                                <i class="fas fa-star star" data-rating="1"></i>
                                <i class="fas fa-star star" data-rating="2"></i>
                                <i class="fas fa-star star" data-rating="3"></i>
                                <i class="fas fa-star star" data-rating="4"></i>
                                <i class="fas fa-star star" data-rating="5"></i>
                                <i class="fas fa-star star" data-rating="6"></i>
                                <i class="fas fa-star star" data-rating="7"></i>
                                <i class="fas fa-star star" data-rating="8"></i>
                                <i class="fas fa-star star" data-rating="9"></i>
                                <i class="fas fa-star star" data-rating="10"></i>
                            </div>
                            
                            <div id="rating-description" class="mb-6">
                                <div class="bg-gradient-to-r from-red-100 to-green-100 border border-gray-200 rounded-xl px-6 py-4 shadow-sm">
                                    <div class="flex items-center justify-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full">1</span>
                                            <span class="text-red-600 font-semibold">malo</span>
                                        </div>
                                        <div class="flex-1 h-px bg-gradient-to-r from-red-300 to-green-300"></div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-green-600 font-semibold">excelente</span>
                                            <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full">10</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-center">
                                <button id="submit-btn" onclick="enviarRespuesta()" class="btn-primary px-8 py-4 text-lg font-semibold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center" disabled>
                                    <i class="fas fa-arrow-right mr-2 text-lg"></i>
                                    Continuar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function configurarEventosEstrellas() {
            const stars = document.querySelectorAll('.star');
            const submitBtn = document.getElementById('submit-btn');
            let selectedRating = 0;

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    selectedRating = parseInt(star.dataset.rating);
                    console.log('Clic en estrella:', selectedRating); // Debug
                    seleccionarRating(selectedRating);
                    submitBtn.disabled = false;
                });

                star.addEventListener('mouseenter', () => {
                    hoverStar(parseInt(star.dataset.rating));
                });
            });

            document.getElementById('star-rating').addEventListener('mouseleave', () => {
                if (selectedRating > 0) {
                    seleccionarRating(selectedRating);
                } else {
                    unselectAllStars();
                }
            });
        }

        function seleccionarRating(rating) {
            const stars = document.querySelectorAll('.star');

            console.log('Seleccionando rating:', rating); // Debug

            // Primero limpiar todas las estrellas
            stars.forEach(star => {
                star.classList.remove('selected', 'hovered');
            });

            // Luego seleccionar solo las estrellas hasta el rating seleccionado
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('selected');
                }
            });

            console.log('Estrellas con clase selected:', document.querySelectorAll('.star.selected').length); // Debug
        }

        function hoverStar(rating) {
            const stars = document.querySelectorAll('.star');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('hovered');
                } else {
                    star.classList.remove('hovered');
                }
            });
        }

        function unselectAllStars() {
            const stars = document.querySelectorAll('.star');

            stars.forEach(star => {
                star.classList.remove('selected', 'hovered');
            });
        }

        function generarPreguntaSiNo() {
            return `
                <div class="text-center">
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">¿Recomendarías a Konkret a un colega o contacto?</h3>
                    </div>
                    
                    <div class="max-w-2xl mx-auto mb-8">
                        <div class="bg-white rounded-xl p-8 border-2 border-gray-200 shadow-lg">
                            <div class="flex justify-center gap-8 mb-8">
                                <button id="btn-si" class="rating-button w-24 h-24 bg-green-500 text-white text-2xl font-bold rounded-full transition-all duration-300 flex flex-col items-center justify-center">
                                    <i class="fas fa-thumbs-up text-2xl mb-1"></i>
                                    <div class="text-sm font-medium">Sí</div>
                                </button>
                                <button id="btn-no" class="rating-button w-24 h-24 bg-red-500 text-white text-2xl font-bold rounded-full transition-all duration-300 flex flex-col items-center justify-center">
                                    <i class="fas fa-thumbs-down text-2xl mb-1"></i>
                                    <div class="text-sm font-medium">No</div>
                                </button>
                            </div>
                            
                            <div class="flex justify-center">
                                <button id="submit-btn" onclick="enviarRespuesta()" class="btn-primary px-8 py-4 text-lg font-semibold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center" disabled>
                                    <i class="fas fa-arrow-right mr-2 text-lg"></i>
                                    Continuar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function generarPreguntaAbierta() {
            return `
                <div class="text-center">
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">¿Qué podríamos hacer para mejorar tu experiencia?</h3>
                        <p class="text-xl text-gray-600 mb-6">Tu opinión es muy valiosa para nosotros</p>
                        <p class="text-lg text-gray-500">Comparte cualquier sugerencia o comentario</p>
                    </div>
                    
                    <div class="max-w-3xl mx-auto mb-8">
                        <div class="bg-white rounded-xl p-8 border-2 border-gray-200 shadow-lg">
                            <textarea id="respuesta-texto" 
                                      class="w-full h-40 p-6 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all duration-300 shadow-sm focus:shadow-lg"
                                      placeholder="Escribe tu sugerencia o comentario aquí..."></textarea>
                            
                            <div class="mt-6 flex justify-center">
                                <button id="submit-btn" onclick="enviarRespuesta()" class="btn-primary px-8 py-4 text-lg font-semibold rounded-xl disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center" disabled>
                                    <i class="fas fa-arrow-right mr-2 text-lg"></i>
                                    Finalizar Encuesta
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function configurarEventosSiNo() {
            const btnSi = document.getElementById('btn-si');
            const btnNo = document.getElementById('btn-no');
            const submitBtn = document.getElementById('submit-btn');

            btnSi.addEventListener('click', () => {
                btnSi.classList.add('selected');
                btnNo.classList.remove('selected');
                submitBtn.disabled = false;
            });

            btnNo.addEventListener('click', () => {
                btnNo.classList.add('selected');
                btnSi.classList.remove('selected');
                submitBtn.disabled = false;
            });
        }

        function configurarEventosTextarea() {
            const textarea = document.getElementById('respuesta-texto');
            const submitBtn = document.getElementById('submit-btn');

            if (textarea && submitBtn) {
                const updateButtonState = () => {
                    submitBtn.disabled = textarea.value.trim() === '';
                };

                textarea.addEventListener('input', updateButtonState);
                textarea.addEventListener('paste', () => setTimeout(updateButtonState, 10));
                textarea.addEventListener('cut', () => setTimeout(updateButtonState, 10));
                textarea.addEventListener('keyup', updateButtonState);
            }
        }

        function generarContenidoSimple() {
            return `
                <div class="text-center">
                    <div class="mb-8">
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">¡Gracias por tu confianza!</h3>
                        <p class="text-xl text-gray-600 mb-6">¿Podrías ayudarnos completando una breve encuesta?</p>
                        <p class="text-lg text-gray-500">Solo te tomará 1 minuto. 😊</p>
                    </div>
                    
                    <div class="max-w-3xl mx-auto mb-8">
                        <div class="bg-gray-50 rounded-xl p-8 border-2 border-gray-200">
                            <h4 class="text-xl font-semibold text-gray-800 mb-4">Comparte tu experiencia con nosotros</h4>
                            <p class="text-gray-700 mb-6">
                                Tu opinión es muy valiosa para nosotros. Por favor, comparte cualquier comentario, 
                                sugerencia o experiencia que hayas tenido con nuestros servicios.
                            </p>
                            
                            <textarea id="respuesta-texto" 
                                      class="w-full h-40 p-6 border-2 border-gray-300 rounded-xl focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500 resize-none transition-all duration-300 shadow-sm focus:shadow-lg"
                                      placeholder="Escribe tu comentario, sugerencia o experiencia aquí..."></textarea>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Tu respuesta será confidencial y nos ayudará a mejorar nuestros servicios
                        </p>
                    </div>

                    <div class="flex justify-center">
                        <button onclick="enviarRespuesta()" 
                                class="btn-primary disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                                id="submit-btn" disabled>
                            <i class="fas fa-paper-plane mr-2 text-lg"></i>
                            Enviar Respuesta
                        </button>
                    </div>
                </div>
            `;
        }

        function mostrarEncuestaCompletada() {
            const questionContent = document.getElementById('question-content');
            questionContent.innerHTML = `
                <div class="text-center">
                    <div class="mb-6">
                        <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                        <h3 class="text-3xl font-bold text-gray-800 mb-2">¡Encuesta Completada!</h3>
                        <p class="text-lg text-gray-600">Gracias por tu tiempo y valiosa opinión</p>
                    </div>
                    
                    <div class="bg-[#0E1D49] border border-[#0E1D49] rounded-lg p-6 mb-6">
                        <p class="text-white">
                            <i class="fas fa-info-circle mr-2"></i>
                            Tus respuestas han sido guardadas correctamente. Tu feedback es muy importante para nosotros.
                        </p>
                    </div>

                    <div class="flex justify-center">
                        <button onclick="window.close()"
                            class="bg-red-600 hover:bg-red-700 hover:scale-105 text-white font-bold py-3 px-8 rounded-lg transition duration-200 flex items-center justify-center">
                            <i class="fas fa-times mr-2 text-lg"></i>
                            Cerrar Ventana
                        </button>
                    </div>
                </div>
            `;
            actualizarProgreso('completado');
        }


        function enviarRespuesta() {
            const submitBtn = document.getElementById('submit-btn');
            const loading = document.getElementById('loading');
            const successMessage = document.getElementById('success-message');
            const errorMessage = document.getElementById('error-message');

            // Ocultar mensajes anteriores
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');

            // Mostrar loading
            submitBtn.disabled = true;
            loading.classList.remove('hidden');

            // Obtener respuesta según el tipo de pregunta
            let respuesta = '';

            // Si hay estrellas seleccionadas, usar esa respuesta
            const selectedStars = document.querySelectorAll('.star.selected');
            if (selectedStars.length > 0) {
                // Contar cuántas estrellas están seleccionadas para obtener el rating correcto
                respuesta = selectedStars.length.toString();
                console.log('Estrellas seleccionadas:', selectedStars.length); // Debug
                console.log('Respuesta a enviar:', respuesta); // Debug
            }
            // Si hay botones Sí/No seleccionados
            else if (document.getElementById('btn-si') && document.getElementById('btn-si').classList.contains('selected')) {
                respuesta = 'si';
            } else if (document.getElementById('btn-no') && document.getElementById('btn-no').classList.contains('selected')) {
                respuesta = 'no';
            }
            // Si hay textarea
            else {
                const textarea = document.getElementById('respuesta-texto');
                if (textarea) {
                    respuesta = textarea.value.trim();
                }
            }

            console.log('Respuesta a enviar:', respuesta); // Debug

            // Enviar respuesta
            fetch(`/encuesta/${envioId}/responder`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        respuesta: respuesta
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loading.classList.add('hidden');

                    if (data.success) {
                        if (data.esCompletada) {
                            mostrarEncuestaCompletada();
                        } else {
                            cargarPregunta(data.siguientePregunta);
                            actualizarProgreso(data.siguientePregunta);
                            // Asegurar que el botón esté deshabilitado en la nueva pregunta
                            const newSubmitBtn = document.getElementById('submit-btn');
                            if (newSubmitBtn) {
                                newSubmitBtn.disabled = true;
                            }
                            // No mostrar mensaje de éxito intermedio, solo el del final
                        }
                    } else {
                        errorMessage.classList.remove('hidden');
                        document.getElementById('error-text').textContent = data.message;
                        submitBtn.disabled = false;
                    }
                })
                .catch(error => {
                    loading.classList.add('hidden');
                    errorMessage.classList.remove('hidden');
                    document.getElementById('error-text').textContent = 'Error de conexión. Intente nuevamente.';
                    submitBtn.disabled = false;
                });
        }

        function actualizarProgreso(pregunta) {
            const progreso = preguntasMap[pregunta] || 0;
            const porcentaje = (progreso / 8) * 100; // 8 pasos totales

            document.getElementById('progress-bar').style.width = porcentaje + '%';
            document.getElementById('progress-text').textContent = Math.round(porcentaje) + '%';
        }
    </script>
</body>

</html>