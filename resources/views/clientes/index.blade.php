<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ __('Gestión de Clientes') }}
                </h2>
                <p class="text-gray-600 mt-1">Administra la información de tus clientes</p>
            </div>
            <div class="flex items-center space-x-3">
              
                <a href="{{ route('clientes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nuevo Cliente
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estadísticas rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Clientes</p>
                            <p class="text-2xl font-bold mt-1">{{ $clientes->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd"/>
                              </svg>
                              
                        </div>
                    </div>
                </div>

                <div class="bg-green-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Activos</p>
                            <p class="text-2xl font-bold mt-1">{{ $clientes->count() }}</p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
                              </svg>                              
                            
                        </div>
                    </div>
                </div>


            </div>

            <!-- Mensaje de éxito -->
            @if(session('success'))
                <div class="bg-green-500 border border-green-400 text-white px-6 py-4 rounded-xl mb-6 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Tabla de clientes con DataTables -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
               

                <div class="p-6">
                    <table id="clientes-table" class="w-full display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Información</th>
                                <th>Contacto</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientes as $cliente)
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                                <span class="text-white font-semibold text-sm">{{ strtoupper(substr($cliente->nombre_completo, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $cliente->nombre_completo }}</div>
                                                <div class="text-sm text-gray-500">{{ $cliente->razon_social }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <div class="text-sm text-gray-900">
                                                <span class="font-medium">Asesor:</span> {{ $cliente->asesor_comercial }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <span class="font-medium">Puesto:</span> {{ $cliente->puesto }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="space-y-1">
                                            <div class="text-sm text-gray-900">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $cliente->celular }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                                {{ $cliente->correo }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('clientes.show', $cliente) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                            <a href="{{ route('clientes.edit', $cliente) }}" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 transition-colors duration-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>
                                            <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 transition-colors duration-200">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
             

            <!-- Script para inicializar DataTables -->
            <script>
                $(document).ready(function() {
                    $('#clientes-table').DataTable({
                        responsive: true,
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
                        },
                        pageLength: 10,
                        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Todos"]],
                        order: [[0, 'asc']],
                        columnDefs: [
                            {
                                targets: -1,
                                orderable: false,
                                searchable: false
                            }
                        ],
                        initComplete: function() {
                            // Actualizar contador de total de clientes
                            $('#total-clientes').text(this.api().data().count());
                        }
                    });
                });
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
                    padding: 4px 8px !important;
                    margin: 0 8px !important;
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