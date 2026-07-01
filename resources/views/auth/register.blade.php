<!DOCTYPE html>
<html lang="pt" class="h-full bg-zinc-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta - BarberVibe</title>
    <!-- Integração direta com os assets locais via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full bg-zinc-950 text-zinc-100 flex items-center justify-center font-sans antialiased">

    <div class="max-w-md w-full p-6 sm:p-8 bg-zinc-900 border border-zinc-800 rounded-2xl shadow-2xl space-y-6">

        <!-- Logotipo e Cabeçalho -->
        <div class="text-center space-y-2">
            <span class="text-amber-500 font-extrabold text-3xl tracking-widest block">BARBERVIBE</span>
            <h2 class="text-xl font-bold text-zinc-100">Crie a sua Conta Premium</h2>
            <p class="text-xs text-zinc-400">Cadastre-se para agendar cortes e serviços online em poucos segundos</p>
        </div>

        <!-- Formulário de Registo -->
        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Nome Completo -->
            <div>
                <label for="name" class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Nome
                    Completo</label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="Ex: João Silva"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('name') border-rose-500 focus:ring-rose-500 @enderror">
                @error('name')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- E-mail -->
            <div>
                <label for="email"
                    class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Endereço de
                    E-mail</label>
                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                    placeholder="seu.email@exemplo.com"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('email') border-rose-500 focus:ring-rose-500 @enderror">
                @error('email')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Senha -->
            <div>
                <label for="password"
                    class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Senha</label>
                <input type="password" name="password" id="password" required placeholder="No mínimo 6 caracteres"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('password') border-rose-500 focus:ring-rose-500 @enderror">
                @error('password')
                    <span class="text-xs text-rose-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirmação de Senha -->
            <div>
                <label for="password_confirmation"
                    class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider mb-2">Confirmar
                    Senha</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    placeholder="Repita a sua senha"
                    class="w-full bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-zinc-100 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
            </div>

            <!-- Botão de Cadastro -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-zinc-950 font-black py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-amber-500/10 hover:shadow-amber-500/20 active:scale-[0.98]">
                    Criar Minha Conta
                </button>
            </div>
        </form>

        <!-- Link de Retorno para o Login -->
        <div class="text-center pt-2 border-t border-zinc-800/60">
            <p class="text-xs text-zinc-400">Já possui uma conta?
                <a href="{{ route('login') }}" class="text-amber-500 font-bold hover:underline">Faça Login</a>
            </p>
        </div>
    </div>
</body>

</html>