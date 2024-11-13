<x-guest-layout>
    {{-- <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card> --}}

    <div>
        <div class="min-h-screen flex flex-col items-center justify-center bg-violet-950 p-4">
            <div
                class="bg-white grid md:grid-cols-3 items-center gap-4 max-w-5xl max-md:max-w-lg w-full p-4 m-4 shadow-lg rounded-xl">
                <div class="h-max bg-violet-950 rounded-xl lg:p-10 p-8 relative flex justify-center">
                    <div class="absolute top-4 left-0 flex gap-1 w-full">
                        <div class="w-full h-1 bg-orange-500"></div>
                        <div class="w-full h-1 bg-green-500"></div>
                        <div class="w-full h-1 bg-blue-500"></div>
                    </div>
                    <img src="https://readymadeui.com/signin-image.webp" class="h-64 object-contain"
                        alt="login-image" />
                </div>

                <div class="w-full px-4 py-4 col-span-2">
                    <x-validation-errors class="mb-4" />
                    @session('status')
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ $value }}
                        </div>
                    @endsession
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="flex gap-4 items-center">
                            <img class="w-20" src="/assets/img/logo.png" alt="">
                            <div>
                                <h3 class="text-gray-800 text-3xl font-extrabold">SysAsis</h3>
                                <h3 class="text-gray-800 text-lg">Sistema para Control de Asistencias</h3>
                            </div>
                        </div>

                        <div class="mb-8">
                            <p class="text-sm mt-4 text-gray-800">
                                ¿Olvidaste tu contraseña? Ningún problema. Simplemente háganos saber su dirección de
                                correo electrónico y le enviaremos un enlace para restablecer su contraseña que le
                                permitirá elegir una nueva.
                            </p>
                        </div>

                        <div>
                            <label class="text-gray-800 text-xs block mb-2">Correo electrónico</label>
                            <div class="relative flex items-center">
                                <input type="email" name="email" :value="old('email')" required autofocus
                                    autocomplete="username"
                                    class="w-full text-gray-800 text-sm border-b border-gray-300 focus:border-blue-600 px-2 py-3 outline-none rounded-lg"
                                    placeholder="example@company.com" />
                                <svg xmlns="http://www.w3.org/2000/svg" fill="#bbb" stroke="#bbb"
                                    class="w-[18px] h-[18px] absolute right-2" viewBox="0 0 682.667 682.667">
                                    <defs>
                                        <clipPath id="a" clipPathUnits="userSpaceOnUse">
                                            <path d="M0 512h512V0H0Z" data-original="#000000"></path>
                                        </clipPath>
                                    </defs>
                                    <g clip-path="url(#a)" transform="matrix(1.33 0 0 -1.33 0 682.667)">
                                        <path fill="none" stroke-miterlimit="10" stroke-width="40"
                                            d="M452 444H60c-22.091 0-40-17.909-40-40v-39.446l212.127-157.782c14.17-10.54 33.576-10.54 47.746 0L492 364.554V404c0 22.091-17.909 40-40 40Z"
                                            data-original="#000000"></path>
                                        <path
                                            d="M472 274.9V107.999c0-11.027-8.972-20-20-20H60c-11.028 0-20 8.973-20 20V274.9L0 304.652V107.999c0-33.084 26.916-60 60-60h392c33.084 0 60 26.916 60 60v196.653Z"
                                            data-original="#000000"></path>
                                    </g>
                                </svg>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit"
                                class="w-full shadow-xl py-2.5 px-4 text-sm tracking-wide rounded-md text-white bg-gradient-to-r from-orange-500 via-green-400 to-blue-400 focus:outline-none hover:scale-[1.04] duration-300 hover:shadow-xl">
                                Enviar enlace para restablecer contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-xs md:text-sm text-gray-300 rounded-lg">
                <div class="text-center">
                    ©
                    2023 -
                    <script>
                        document.write(new Date().getFullYear())
                    </script>, made by <a
                        href="{{ !empty(config('variables.creatorUrl')) ? config('variables.creatorUrl') : '' }}"
                        target="_blank" class="hover:text-blue-400">Dynamus Developer Team</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
