<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskly</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-white font-sans antialiased">

<div class="min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-4xl bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex">

        {{-- Lado esquerdo — visual --}}
        <div class="w-1/2 bg-[#1a1a2e] p-10 flex flex-col justify-between min-h-[560px]">
            <span class="text-white text-xl font-bold">Taskly</span>

            {{-- Ilustração minimalista --}}
            <div class="flex-1 flex items-center justify-center">
                <div class="relative w-64 h-64">
                    <div class="absolute top-8 left-8 w-24 h-24 bg-[#059669] rounded-full opacity-80"></div>
                    <div class="absolute top-16 right-8 w-16 h-16 bg-emerald-400 rounded-full opacity-60"></div>
                    <div class="absolute bottom-8 left-16 w-20 h-20 bg-emerald-300 rounded-full opacity-70"></div>
                    <div class="absolute bottom-16 right-12 w-10 h-10 bg-white rounded-full opacity-20"></div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2
                                w-12 h-12 bg-white rounded-full opacity-10"></div>
                    {{-- Linha decorativa --}}
                    <svg class="absolute inset-0 w-full h-full" viewBox="0 0 256 256">
                        <path d="M40 180 Q80 100 128 128 Q176 156 216 80"
                              fill="none" stroke="white" stroke-width="1.5"
                              stroke-dasharray="4 4" opacity="0.3"/>
                    </svg>
                </div>
            </div>

            <p class="text-gray-400 text-sm">Organiza o teu dia, todos os dias.</p>
        </div>

        {{-- Lado direito — CTA --}}
        <div class="w-1/2 p-10 flex flex-col justify-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-3">Foca no que importa</h1>
            <p class="text-gray-400 text-sm mb-8 leading-relaxed">
                O Taskly é uma app de gestão de tarefas simples e intuitiva,
                desenhada para te ajudar a manter o foco e a produtividade.
            </p>

            <a href="{{ route('register') }}"
               class="block w-full text-center bg-[#059669] hover:bg-[#047d57]
                      text-white font-semibold py-3 rounded-xl transition mb-3">
                Começar agora
            </a>

            <p class="text-center text-sm text-gray-400">
                Já tens conta?
                <a href="{{ route('login') }}" class="text-[#059669] hover:underline font-medium">
                    Iniciar sessão
                </a>
            </p>
        </div>
    </div>
</div>

</body>
</html>