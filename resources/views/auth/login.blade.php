@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="max-w-sm mx-auto mt-16 py-10 px-8 rounded-3xl shadow-2xl
                bg-white dark:bg-gray-900
                border border-gray-300 dark:border-gray-700
                text-gray-900 dark:text-gray-100
                transition-colors duration-500
                animate-fadeInSlideUp"
        style="animation-fill-mode: forwards;">
        <h1
            class="text-3xl font-extrabold mb-12 text-center tracking-tight flex justify-center items-center space-x-3 select-none">
            <i class="fas fa-user-lock text-blue-600 dark:text-blue-400 text-3xl animate-iconBounce"></i>
            <span>Login</span>
        </h1>

        @if (session('error'))
            <x-alert type="error" class="mb-6">{{ session('error') }}</x-alert>
        @endif

        @if ($errors->any())
            <div
                class="mb-6 bg-red-100 text-red-800 p-4 rounded-lg text-sm border border-red-300 dark:bg-red-900 dark:text-red-100 dark:border-red-600">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" novalidate class="space-y-10">
            @csrf

            <div class="relative z-0 w-full group">
                <input id="email" name="email" type="email" placeholder=" " required autocomplete="email"
                    value="{{ old('email') }}"
                    class="block py-3 pl-10 pr-3 w-full text-base text-gray-900 bg-transparent border-0 border-b-2
                           {{ $errors->has('email') ? 'border-red-500 dark:border-red-400' : 'border-gray-300 dark:border-gray-600' }}
                           appearance-none dark:text-gray-100 rounded-lg
                           dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer transition-colors" />
                <label for="email"
                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] left-0
                            peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-placeholder-shown:left-10
                            peer-focus:-translate-y-7 peer-focus:scale-75 peer-focus:text-blue-600 dark:peer-focus:text-blue-400 peer-focus:left-0
                            peer-not-placeholder-shown:left-0 select-none">
                    Email
                </label>
                <i
                    class="fas fa-envelope absolute left-2 top-4 text-gray-400 peer-focus:text-blue-600 dark:peer-focus:text-blue-400 transition-all duration-300 peer-focus:animate-iconWiggle"></i>
                <span
                    class="absolute bottom-0 left-0 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-full transition-all duration-300 peer-focus:w-full"></span>
                @error('email')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="relative z-0 w-full group">
                <input id="password" name="password" type="password" placeholder=" " required
                    autocomplete="current-password"
                    class="block py-3 pl-10 pr-10 w-full text-base text-gray-900 bg-transparent border-0 border-b-2
               {{ $errors->has('password') ? 'border-red-500 dark:border-red-400' : 'border-gray-300 dark:border-gray-600' }}
               appearance-none dark:text-gray-100 rounded-lg
               dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer transition-colors" />
                <label for="password"
                    class="absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-7 scale-75 top-3 -z-10 origin-[0] left-0
                peer-placeholder-shown:translate-y-0 peer-placeholder-shown:scale-100 peer-placeholder-shown:left-10
                peer-focus:-translate-y-7 peer-focus:scale-75 peer-focus:text-blue-600 dark:peer-focus:text-blue-400 peer-focus:left-0
                peer-not-placeholder-shown:left-0 select-none">
                    Password
                </label>

                <i
                    class="fas fa-key absolute left-2 top-4 text-gray-400 peer-focus:text-blue-600 dark:peer-focus:text-blue-400 transition-all duration-300 peer-focus:animate-iconWiggle"></i>

                <!-- 👇 NEW: toggle icon -->
                <button type="button" onclick="togglePasswordVisibility()" tabindex="-1"
                    class="absolute right-2 top-4 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none transition">
                    <i id="toggle-password-icon" class="fas fa-eye"></i>
                </button>

                <span
                    class="absolute bottom-0 left-0 h-0.5 bg-blue-600 dark:bg-blue-400 rounded-full transition-all duration-300 peer-focus:w-full"></span>

                @error('password')
                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                @enderror
            </div>


            <div class="flex items-center justify-between text-sm mt-4">
                <div class="flex items-center">
                    <input type="checkbox" id="remember_me" name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600">
                    <label for="remember_me" class="ml-2 text-gray-600 dark:text-gray-400 select-none">Remember me</label>
                </div>
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Forgot password?</a>
            </div>

            <button type="submit"
                class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800
                        focus:outline-none focus:ring-4 focus:ring-blue-400 dark:focus:ring-blue-300
                        text-white font-semibold rounded-xl shadow-lg hover:shadow-xl active:shadow-none
                        transform hover:-translate-y-0.5 active:translate-y-0 transition duration-300
                        flex items-center justify-center space-x-3 group">
                <i
                    class="fas fa-arrow-right-to-bracket animate-iconPulse group-hover:animate-none group-active:animate-none group-hover:scale-110 transition-transform duration-200"></i>
                <span>Login</span>
            </button>
        </form>
    </div>

    <style>
        @keyframes fadeInSlideUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeInSlideUp {
            animation: fadeInSlideUp 0.6s ease forwards;
        }

        @keyframes iconBounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        .animate-iconBounce {
            animation: iconBounce 2s ease-in-out infinite;
        }

        @keyframes iconPulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        .animate-iconPulse {
            animation: iconPulse 1.5s ease-in-out infinite;
        }

        @keyframes iconWiggle {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-5deg);
            }

            75% {
                transform: rotate(5deg);
            }
        }

        .animate-iconWiggle {
            animation: iconWiggle 0.3s ease-in-out;
        }

        input:focus {
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.6);
            transition: box-shadow 0.3s ease;
        }
    </style>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const icon = document.getElementById("toggle-password-icon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

@endsection
