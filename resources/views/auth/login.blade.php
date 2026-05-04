<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly — Iniciar sessão</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white font-sans antialiased" style="font-family: 'Plus Jakarta Sans', sans-serif;">

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex">

        {{-- Lado esquerdo --}}
        <div class="w-1/2 bg-[#1a1a2e] p-10 flex flex-col justify-between min-h-[560px]">
            <span class="text-white text-xl font-bold">Taskly</span>

            <div class="flex-1 flex items-center justify-center">
                <div class="relative w-64 h-64">
                    <div class="absolute top-8 left-8 w-24 h-24 bg-[#059669] rounded-full opacity-80"></div>
                    <div class="absolute top-16 right-8 w-16 h-16 bg-emerald-400 rounded-full opacity-60"></div>
                    <div class="absolute bottom-8 left-16 w-20 h-20 bg-emerald-300 rounded-full opacity-70"></div>
                    <div class="absolute bottom-16 right-12 w-10 h-10 bg-white rounded-full opacity-20"></div>
                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 256 256">
                        <path d="M40 180 Q80 100 128 128 Q176 156 216 80"
                              fill="none" stroke="white" stroke-width="1.5"
                              stroke-dasharray="4 4" opacity="0.3"/>
                    </svg>
                </div>
            </div>

            <p class="text-gray-400 text-sm">Organiza o teu dia, todos os dias.</p>
        </div>

        {{-- Lado direito — Login --}}
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Iniciar sessão</h1>
            <p class="text-gray-400 text-sm mb-8">Bem-vindo de volta ao Taskly.</p>

            {{-- Erros --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#059669] transition
                                  {{ $errors->has('email') ? 'border-red-300' : '' }}">
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-[#059669] hover:underline">
                                Esqueceste a password?
                            </a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#059669] transition">
                </div>

                {{-- Lembrar --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                           class="rounded border-gray-300 text-[#059669] focus:ring-[#059669]">
                    <label for="remember" class="text-sm text-gray-500">Lembrar-me</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full bg-[#059669] hover:bg-[#047d57] text-white
                               font-semibold py-3 rounded-xl transition mt-2">
                    Iniciar sessão
                </button>
            </form>

            <p class="text-center text-sm text-gray-400 mt-6">
                Não tens conta?
                <a href="{{ route('register') }}" class="text-[#059669] hover:underline font-medium">
                    Registar
                </a>
            </p>
        </div>
    </div>
</div>

</body>
</html>