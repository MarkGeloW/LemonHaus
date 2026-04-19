<x-guest-layout>
    <div class="min-h-screen w-full bg-gradient-to-br from-yellow-50 to-orange-100 flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-xl bg-white rounded-3xl shadow-2xl overflow-hidden">

            <div class="bg-yellow-400 px-10 py-10 text-center">
                <div class="mx-auto w-20 h-20 bg-white rounded-full flex items-center justify-center mb-5 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-10 h-10 text-yellow-500"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 21h8M9 18h6v-3H9v3Zm8.5-3a3.5 3.5 0 0 0 .62-6.944A4.998 4.998 0 0 0 8.07 6.23 4 4 0 0 0 6.5 14.999" />
                    </svg>
                </div>

                <h1 class="text-4xl font-extrabold text-white tracking-wide">
                    LEMONHAUS
                </h1>

                <p class="mt-2 text-yellow-100 text-lg">
                    Internal Management System
                </p>
            </div>

            <div class="p-10">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                  <div>
    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
        Username
    </label>

    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="w-5 h-5 text-gray-400"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19a4 4 0 0 0-8 0m4-8a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
            </svg>
        </div>

        <input
            id="username"
            name="username"
            type="text"
            value="{{ old('username') }}"
            required
            autofocus
            autocomplete="username"
            placeholder="Enter your username"
            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
        />
    </div>

    <x-input-error :messages="$errors->get('username')" class="mt-2" />
</div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password
                        </label>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 11V8a4 4 0 1 0-8 0v3m-2 0h12a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2Z" />
                                </svg>
                            </div>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 transition"
                            />
                        </div>

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input
                                id="remember_me"
                                type="checkbox"
                                name="remember"
                                class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-400"
                            >
                            <span class="ml-2 text-sm text-gray-600">
                                Remember me
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-sm text-gray-600 hover:text-gray-900 underline">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 rounded-xl bg-yellow-500 hover:bg-yellow-600 text-white font-bold text-lg shadow-md transition"
                    >
                        Sign In
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500 mb-3">Authorized Roles:</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <span class="px-3 py-1 bg-gray-100 rounded-md text-sm">admin</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-md text-sm">cashier</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-md text-sm">kitchen</span>
                        <span class="px-3 py-1 bg-gray-100 rounded-md text-sm">inventory</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>