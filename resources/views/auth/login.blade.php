<x-guest-layout>
    <div class="flex h-screen bg-purple-700">
        <div class="w-full max-w-xs m-auto bg-indigo-100 rounded p-5">
            <header>
                <img class="w-20 mx-auto mb-5" src="assets/img/logo.png" />
            </header>
            <x-validation-errors class="mb-4" />
            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession
            <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label class="block mb-2 text-indigo-500" for="username">Username</label>
                <input class="w-full p-2 mb-6 text-indigo-700 border-b-2 border-indigo-500 outline-none focus:bg-gray-300" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
            </div>
            <div>
                <label class="block mb-2 text-indigo-500" for="password">Password</label>
                <input class="w-full p-2 mb-2 text-indigo-700 border-b-2 border-indigo-500 outline-none focus:bg-gray-300" type="password" name="password" required autocomplete="current-password">
            </div>
            <div class="block mb-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
            <div>
                <input class="w-full bg-indigo-700 hover:bg-pink-700 text-white font-bold py-2 px-4 mb-6 rounded" type="submit" value="Log in">
            </div>

            </form>
            <footer>
            @if (Route::has('password.request'))
                <a class="text-indigo-700 hover:text-pink-700 text-sm float-left" href="{{ route('password.request') }}">Forgot Password?</a>
            @endif

            {{-- <a class="text-indigo-700 hover:text-pink-700 text-sm float-right" href="#">Create Account</a> --}}
            </footer>
        </div>
    </div>
    </x-guest-layout>
