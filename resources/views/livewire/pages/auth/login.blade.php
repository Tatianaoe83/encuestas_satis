<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public bool $showPassword = false;
    public bool $isLoading = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();
        $this->isLoading = true;

        try {
            $this->form->authenticate();
            Session::regenerate();
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
        } catch (\Exception $e) {
            $this->isLoading = false;
            session()->flash('error', 'Credenciales inválidas. Por favor, inténtalo de nuevo.');
        }
    }

    public function togglePassword(): void
    {
        $this->showPassword = !$this->showPassword;
    }
}; ?>

<div class="min-h-screen flex bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Circles -->
        <div class="absolute top-20 left-20 h-32 w-32 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full opacity-10 animate-pulse"></div>
        <div class="absolute top-40 right-32 h-24 w-24 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute bottom-32 left-32 h-20 w-20 bg-gradient-to-r from-pink-400 to-red-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute bottom-20 right-20 h-28 w-28 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full opacity-10 animate-pulse" style="animation-delay: 3s;"></div>
        
        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-5">
            <svg class="w-full h-full" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                        <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5"/>
                    </pattern>
                </defs>
                <rect width="100" height="100" fill="url(#grid)" />
            </svg>
        </div>
    </div>

    <!-- Left Side - Visual Content -->
    <div class="hidden lg:flex lg:w-1/2 flex-col justify-center px-12 py-12 relative">
        <!-- Logo Section with Animation -->
        <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="h-16 w-16 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-2xl transform hover:rotate-12 transition-transform duration-300">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <!-- Glow Effect -->
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-600 from-gray-900 to-gray-600 bg-clip-text text-transparent">Encuestas</h1>
                    <p class="text-gray-600 text-sm font-medium">Sistema de Encuestas de Satisfacción</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards with Hover Effects -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <!-- Active Surveys Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-xl border border-white/20 transform hover:scale-105 hover:shadow-2xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-blue-600 transition-colors duration-300">24</p>
                        <p class="text-xs text-gray-500 font-medium">Encuestas Activas</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full transition-all duration-1000 ease-out" style="width: 75%"></div>
                </div>
            </div>

            <!-- Response Rate Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-6 shadow-xl border border-white/20 transform hover:scale-105 hover:shadow-2xl transition-all duration-300 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="h-12 w-12 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-gray-900 group-hover:text-emerald-600 transition-colors duration-300">89%</p>
                        <p class="text-xs text-gray-500 font-medium">Tasa de Respuesta</p>
                    </div>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-3 rounded-full transition-all duration-1000 ease-out" style="width: 89%"></div>
                </div>
            </div>
        </div>

        <!-- Enhanced Chart Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-8 shadow-xl border border-white/20 mb-8 transform hover:scale-105 transition-all duration-300">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="h-5 w-5 text-emerald-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Satisfacción General
            </h3>
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <svg class="h-24 w-24 transform -rotate-90" viewBox="0 0 36 36">
                        <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <path class="text-emerald-500" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" stroke-linecap="round">
                            <animate attributeName="stroke-dasharray" from="0, 100" to="85, 100" dur="2s" fill="freeze"/>
                        </path>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-xl font-bold text-gray-900">85%</span>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="h-4 w-4 bg-emerald-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 font-medium">Muy Satisfecho</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">45%</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="h-4 w-4 bg-blue-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 font-medium">Satisfecho</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">40%</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="h-4 w-4 bg-orange-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 font-medium">Neutral</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">10%</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="h-4 w-4 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-600 font-medium">Insatisfecho</span>
                            </div>
                            <span class="text-sm font-bold text-gray-900">5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Welcome Text with Enhanced Typography -->
        <div class="space-y-4">
            <h2 class="text-4xl font-bold text-gray-900 leading-tight">
                ¡Bienvenido de vuelta!
                <span class="block text-2xl text-emerald-600">👋</span>
            </h2>
            <p class="text-lg text-gray-600 leading-relaxed">
                Gestiona tus encuestas de satisfacción de manera más rápida y eficiente. 
                Analiza resultados, mejora la experiencia de tus clientes y toma decisiones basadas en datos.
            </p>
            <div class="flex items-center space-x-4 pt-4">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Análisis en tiempo real</span>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Reportes automáticos</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="flex-1 flex items-center justify-center px-6 py-12 relative">
        <div class="w-full max-w-md">
            <!-- Mobile Logo with Animation -->
            <div class="lg:hidden text-center mb-8">
                <div class="flex items-center justify-center space-x-3 mb-6 transform hover:scale-105 transition-transform duration-300">
                    <div class="relative">
                        <div class="h-14 w-14 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-2xl">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-teal-500 rounded-2xl blur-xl opacity-30 animate-pulse"></div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent">EncuestasPro</h1>
                        <p class="text-gray-600 text-sm font-medium">Sistema de Gestión Inteligente</p>
                    </div>
                </div>
            </div>

            <!-- Enhanced Login Form Card -->
            <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/20 overflow-hidden transform hover:scale-105 transition-all duration-300">
                <!-- Form Header with Gradient -->
                <div class="px-8 py-8 text-center bg-gradient-to-r from-gray-50 via-white to-gray-50 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/5 to-teal-500/5"></div>
                    <div class="relative z-10">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">¡Bienvenido!</h2>
                        <p class="text-gray-600">Gestiona tus encuestas de manera más rápida y eficiente</p>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="px-8 py-8">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    
                    @if (session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm transform animate-pulse">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit="login" class="space-y-6">
                        <!-- Email Address with Enhanced Styling -->
                        <div class="group">
                            <x-input-label for="email" :value="__('Correo electrónico')" class="text-sm font-semibold text-gray-700 mb-2 group-hover:text-emerald-600 transition-colors duration-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-hover:text-emerald-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <x-text-input 
                                    wire:model.live="form.email" 
                                    wire:loading.class="border-emerald-300 focus:border-emerald-500 focus:ring-emerald-500"
                                    id="email" 
                                    class="pl-12 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200 text-sm h-12 group-hover:border-emerald-300" 
                                    type="email" 
                                    name="email" 
                                    required 
                                    autofocus 
                                    autocomplete="username" 
                                    placeholder="tu@email.com" />
                            </div>
                            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
                            <div wire:loading wire:target="form.email" class="mt-2">
                                <div class="flex items-center text-xs text-emerald-600">
                                    <svg class="animate-spin -ml-1 mr-2 h-3 w-3 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Validando...
                                </div>
                            </div>
                        </div>

                        <!-- Password with Enhanced Styling -->
                        <div class="group">
                            <x-input-label for="password" :value="__('Contraseña')" class="text-sm font-semibold text-gray-700 mb-2 group-hover:text-emerald-600 transition-colors duration-200" />
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 group-hover:text-emerald-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <x-text-input 
                                    wire:model="form.password" 
                                    id="password" 
                                    class="pl-12 pr-12 block w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200 text-sm h-12 group-hover:border-emerald-300" 
                                    :type="$showPassword ? 'text' : 'password'"
                                    name="password"
                                    required 
                                    autocomplete="current-password" 
                                    placeholder="Al menos 8 caracteres" />
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <button 
                                        type="button"
                                        wire:click="togglePassword"
                                        class="text-gray-400 hover:text-emerald-600 focus:outline-none focus:text-emerald-600 transition-colors duration-200">
                                        @if($showPassword)
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        @endif
                                    </button>
                                </div>
                            </div>
                            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center group">
                                <input 
                                    wire:model="form.remember" 
                                    id="remember" 
                                    type="checkbox" 
                                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded transition-colors duration-200" 
                                    name="remember">
                                <label for="remember" class="ml-2 block text-sm text-gray-700 group-hover:text-emerald-600 transition-colors duration-200">
                                    {{ __('Recordarme') }}
                                </label>
                            </div>

                    
                        </div>

                        <!-- Enhanced Submit Button -->
                        <div>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-75 cursor-not-allowed"
                                class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 disabled:opacity-75 disabled:cursor-not-allowed shadow-lg hover:shadow-2xl transform hover:-translate-y-1 h-12 overflow-hidden">
                                
                                <!-- Button Background Animation -->
                                <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                
                                <span wire:loading.remove wire:target="login" class="relative z-10 flex items-center">
                                    <svg class="h-5 w-5 text-emerald-200 group-hover:text-white transition-colors duration-200 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    {{ __('Iniciar Sesión') }}
                                </span>
                                
                                <span wire:loading wire:target="login" class="relative z-10 flex items-center">
                                    <svg class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Iniciando sesión...
                                </span>
                            </button>
                        </div>

                        <!-- Enhanced Divider -->
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            
                        </div>

    

    
                    </form>
                </div>
            </div>

            
        </div>
    </div>
</div>
