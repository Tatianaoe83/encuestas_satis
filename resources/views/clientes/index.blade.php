<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/pagination-custom.css') }}">
    @endpush
    
    @push('scripts')
        <script src="{{ asset('js/pagination-enhancements.js') }}"></script>
    @endpush
    
    <x-slot name="header">
        
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
            <div class="flex-1">
                <h2 class="font-bold text-xl sm:text-2xl text-gray-800 leading-tight">
                    {{ __('Gestión de Clientes') }}
                </h2>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Administra la información de tus clientes de manera eficiente</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('clientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-3 sm:px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center group text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1 sm:mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Nuevo Cliente</span>
                    <span class="sm:hidden">Nuevo</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8 lg:py-12">
        <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-12">
            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 mb-8 sm:mb-12">
                <!-- Total Clientes -->
                <div class="bg-blue-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-blue-100 text-xs sm:text-sm font-semibold tracking-wide">Total Clientes</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $total_clientes }}</p>
                            <p class="text-blue-200 text-xs mt-1 truncate">Registros en el sistema</p>
                        </div>
                    </div>
                </div>

                <!-- Clientes Activos -->
                <div class="bg-green-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-green-100 text-xs sm:text-sm font-semibold tracking-wide">Activos</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $total_activos }}</p>
                            <p class="text-green-200 text-xs mt-1 truncate">Disponibles para envíos</p>
                        </div>
                    </div>
                </div>

                <!-- Clientes Inactivos -->
                <div class="bg-orange-600 rounded-lg sm:rounded-xl shadow-lg p-6 sm:p-8 text-white">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-orange-100 text-xs sm:text-sm font-semibold tracking-wide">Inactivos</p>
                            <p class="text-xl sm:text-2xl font-bold mt-1">{{ $total_inactivos }}</p>
                            <p class="text-orange-200 text-xs mt-1 truncate">Pueden ser reactivados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de clientes con DataTables -->
            <div class="bg-white rounded-lg sm:rounded-2xl shadow-xl border border-gray-200 overflow-hidden backdrop-blur-sm">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 sm:px-8 py-4 sm:py-6 border-b border-gray-200">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Todos los Clientes
                    </h3>
                </div>

                <div class="p-6 sm:p-8 overflow-x-auto">
                    <table id="clientes-table" class="w-full display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Información</th>
                                <th>Contacto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 {{ $cliente->trashed() ? 'bg-gray-400' : 'bg-blue-600' }} rounded-full flex items-center justify-center mr-4">
                                                <span class="text-white font-semibold text-sm">{{ strtoupper(substr($cliente->nombre_completo, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold {{ $cliente->trashed() ? 'text-gray-600' : 'text-gray-900' }}">{{ $cliente->nombre_completo }}</div>
                                                <div class="text-sm {{ $cliente->trashed() ? 'text-gray-400' : 'text-gray-500' }}">{{ $cliente->razon_social }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <div class="text-sm {{ $cliente->trashed() ? 'text-gray-600' : 'text-gray-900' }}">
                                                <span class="font-medium">Asesor:</span> {{ $cliente->asesor_comercial }}
                                            </div>
                                            <div class="text-sm {{ $cliente->trashed() ? 'text-gray-400' : 'text-gray-500' }}">
                                                <span class="font-medium">Puesto:</span> {{ $cliente->puesto }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <div class="text-sm {{ $cliente->trashed() ? 'text-gray-600' : 'text-gray-900' }}">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $cliente->celular }}
                                            </div>
                                            <div class="text-sm {{ $cliente->trashed() ? 'text-gray-400' : 'text-gray-500' }}">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $cliente->correo }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col items-center space-y-2">
                                            @if($cliente->trashed())
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-500 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                    INACTIVO
                                                </span>
                                                @if($cliente->deleted_at)
                                                    <div class="text-xs text-red-600 bg-red-50 px-2 py-1 rounded-lg">
                                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $cliente->deleted_at->format('d/m/Y H:i') }}
                                                    </div>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-500 text-white shadow-lg transform hover:scale-105 transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    ACTIVO
                                                </span>
                                               
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('clientes.show', $cliente) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-semibold rounded-lg text-blue-700 bg-blue-50 hover:bg-blue-100 transition-all duration-200 shadow-sm hover:shadow-md">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                            @if(!$cliente->trashed())
                                                <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-semibold rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>
                                                <button type="button" onclick="confirmarInactivar({{ $cliente->idcliente }}, '{{ $cliente->nombre_completo }}')" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-semibold rounded-lg text-orange-700 bg-orange-50 hover:bg-orange-100 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                    </svg>
                                                    Inactivar
                                                </button>
                                            @else
                                                <button type="button" onclick="confirmarReactivar({{ $cliente->idcliente }}, '{{ $cliente->nombre_completo }}')" class="inline-flex items-center px-3 py-2 border border-transparent text-xs font-semibold rounded-lg text-green-700 bg-green-50 hover:bg-green-100 transition-all duration-200 shadow-sm hover:shadow-md">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                    Reactivar
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
         
            <!-- Script para inicializar DataTables y SweetAlert2 -->
            <script>
                $(document).ready(function() {
                    // Inicializar tabla unificada de clientes con paginado mejorado
                    $('#clientes-table').DataTable({
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json',
                            paginate: {
                                first: "Primero",
                                last: "Último",
                                next: "Siguiente",
                                previous: "Anterior"
                            },
                            info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            infoEmpty: "No hay registros disponibles",
                            infoFiltered: "(filtrado de _MAX_ registros totales)",
                            lengthMenu: "Mostrar _MENU_ registros",
                            search: "Buscar:",
                            zeroRecords: "No se encontraron registros coincidentes",
                            processing: "Procesando...",
                            loadingRecords: "Cargando...",
                            emptyTable: "No hay datos disponibles en la tabla"
                        },
                        pageLength: 10,
                        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "Todos"]],
                        order: [[0, 'asc']],
                        columnDefs: [
                            {
                                targets: 3, // Columna de estado (índice 3)
                                orderable: true,
                                searchable: true,
                                className: 'text-center',
                                width: '150px',
                                responsivePriority: 2
                            },
                            {
                                targets: 4, // Columna de acciones (índice 4)
                                orderable: false,
                                searchable: false,
                                className: 'text-center',
                                width: '200px',
                                responsivePriority: 1
                            }
                        ],
                        responsive: {
                            breakpoints: [
                                { name: 'bigdesktop', width: Infinity },
                                { name: 'meddesktop', width: 1480 },
                                { name: 'smalldesktop', width: 1280 },
                                { name: 'medium', width: 1024 },
                                { name: 'tabletl', width: 640 },
                                { name: 'mobilel', width: 480 },
                                { name: 'mobilep', width: 320 }
                            ]
                        },
                        dom: '<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6"<"flex flex-col sm:flex-row sm:items-center gap-4"l><"flex flex-col sm:flex-row sm:items-center gap-4"f>>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6"<"flex items-center"i><"flex items-center"p>>',
                        processing: true,
                        deferRender: true,
                        stateSave: true,
                        stateDuration: 60 * 60 * 24, // 24 horas
                        pagingType: 'full_numbers',
                        drawCallback: function(settings) {
                            // Agregar clases personalizadas después de cada redibujado
                            $('.dataTables_wrapper .dataTables_paginate .paginate_button').addClass('transition-all duration-300');
                        },
                        initComplete: function() {
                            // Actualizar contador de total de clientes
                            $('#total-clientes').text(this.api().data().count());
                            
                            // Agregar tooltips a los botones de paginación
                            $('.dataTables_wrapper .dataTables_paginate .paginate_button').attr('title', function() {
                                var text = $(this).text();
                                if (text.includes('Primero')) return 'Ir a la primera página';
                                if (text.includes('Último')) return 'Ir a la última página';
                                if (text.includes('Siguiente')) return 'Ir a la siguiente página';
                                if (text.includes('Anterior')) return 'Ir a la página anterior';
                                return 'Ir a la página ' + text;
                            });
                        }
                    });
                });

                // Función para confirmar inactivación de cliente
                function confirmarInactivar(id, nombre) {
                    Swal.fire({
                        title: '¿Inactivar Cliente?',
                        html: `¿Estás seguro de que quieres <strong>inactivar</strong> al cliente <strong>"${nombre}"</strong>?<br><br><span class="text-sm text-gray-600">El cliente será marcado como inactivo pero podrás reactivarlo más tarde.</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f97316',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-ban mr-2"></i>Sí, Inactivar',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            title: 'text-gray-800 font-bold',
                            content: 'text-gray-600',
                            confirmButton: 'rounded-lg px-6 py-2 font-semibold',
                            cancelButton: 'rounded-lg px-6 py-2 font-semibold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Crear formulario dinámico para enviar la petición
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/clientes/${id}`;
                            
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            
                            const methodField = document.createElement('input');
                            methodField.type = 'hidden';
                            methodField.name = '_method';
                            methodField.value = 'DELETE';
                            
                            form.appendChild(csrfToken);
                            form.appendChild(methodField);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

                // Función para confirmar reactivación de cliente
                function confirmarReactivar(id, nombre) {
                    Swal.fire({
                        title: '¿Reactivar Cliente?',
                        html: `¿Estás seguro de que quieres <strong>reactivar</strong> al cliente <strong>"${nombre}"</strong>?<br><br><span class="text-sm text-gray-600">El cliente volverá a estar disponible para envíos.</span>`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-check mr-2"></i>Sí, Reactivar',
                        cancelButtonText: '<i class="fas fa-times mr-2"></i>Cancelar',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-2xl',
                            title: 'text-gray-800 font-bold',
                            content: 'text-gray-600',
                            confirmButton: 'rounded-lg px-6 py-2 font-semibold',
                            cancelButton: 'rounded-lg px-6 py-2 font-semibold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Crear formulario dinámico para enviar la petición
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/clientes/${id}/restore`;
                            
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            
                            form.appendChild(csrfToken);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

                // Mostrar mensaje de éxito con SweetAlert2 si existe
                @if(session('success'))
                    Swal.fire({
                        title: '¡Éxito!',
                        text: '{{ session("success") }}',
                        icon: 'success',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#10b981',
                        customClass: {
                            popup: 'rounded-2xl',
                            title: 'text-gray-800 font-bold',
                            confirmButton: 'rounded-lg px-6 py-2 font-semibold'
                        }
                    });
                @endif
            </script>

            <!-- Estilos personalizados para DataTables -->
            <style>
                .dataTables_wrapper .dt-buttons {
                    margin-bottom: 15px;
                }
                
                .dataTables_wrapper .dt-button {
                    background: #3b82f6 !important;
                    color: white !important;
                    border: none !important;
                    padding: 8px 16px !important;
                    border-radius: 6px !important;
                    margin-right: 8px !important;
                    font-size: 14px !important;
                    font-weight: 500 !important;
                    transition: all 0.2s !important;
                }
                
                .dataTables_wrapper .dt-button:hover {
                    background: #2563eb !important;
                    transform: translateY(-1px) !important;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important;
                }
                
                .dataTables_wrapper .dataTables_filter input {
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    padding: 8px 12px !important;
                    margin-left: 8px !important;
                }
                
                .dataTables_wrapper .dataTables_filter input:focus {
                    outline: none !important;
                    border-color: #3b82f6 !important;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                }
                
                .dataTables_wrapper .dataTables_length select {
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    padding: 6px 10px !important;
                    margin-left: 8px !important;
                    margin-right: 8px !important;
                }
                
                .dataTables_wrapper .dataTables_length select:focus {
                    outline: none !important;
                    border-color: #3b82f6 !important;
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
                }
                
                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    border: 1px solid #d1d5db !important;
                    border-radius: 6px !important;
                    padding: 8px 12px !important;
                    margin: 0 2px !important;
                    background: white !important;
                    color: #374151 !important;
                }
                
                .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                    background: #f3f4f6 !important;
                    border-color: #9ca3af !important;
                }
                
                .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                    background: #3b82f6 !important;
                    border-color: #3b82f6 !important;
                    color: white !important;
                }
                
                .dataTables_wrapper .dataTables_info {
                    color: #6b7280 !important;
                    font-size: 14px !important;
                }
                
                #clientes-table {
                    border-collapse: collapse !important;
                    width: 100% !important;
                }
                
                #clientes-table th {
                    background: #f9fafb !important;
                    padding: 12px 16px !important;
                    text-align: left !important;
                    font-weight: 600 !important;
                    color: #374151 !important;
                    border-bottom: 2px solid #e5e7eb !important;
                }
                
                #clientes-table td {
                    padding: 12px 16px !important;
                    border-bottom: 1px solid #f3f4f6 !important;
                }
                
                #clientes-table tbody tr:hover {
                    background: #f9fafb !important;
                }
            </style>
        </div>
    </div>
</x-app-layout> 