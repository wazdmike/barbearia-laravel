<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BarberVibe | Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-zinc-950 text-zinc-100 antialiased">
    <div
        class="flex min-h-screen items-center justify-center bg-[radial-gradient(circle_at_top_left,rgba(245,158,11,0.25),transparent_35%),linear-gradient(135deg,#09090b_0%,#18181b_100%)] px-4 py-10">
        <div
            class="w-full max-w-md rounded-2xl border border-zinc-800 bg-zinc-900/90 p-8 shadow-2xl shadow-black/40 backdrop-blur">
            <div class="mb-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full border border-amber-500/30 bg-amber-500/10 text-amber-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M12 3v18m0 0c-3.5-3-5-5-5-8a4 4 0 1 1 8 0c0 3-1.5 5-3 8Z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold tracking-wide text-zinc-100">BarberVibe</h1>
                <p class="mt-2 text-sm text-zinc-400">Acesso seguro para clientes, barbeiros e administração.</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-rose-800 bg-rose-950/80 p-3 text-sm text-rose-300">
                    <p class="font-medium">Não foi possível entrar.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-zinc-300">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                        class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500" />
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-zinc-300">Senha</label>
                    <input id="password" name="password" type="password" required
                        class="w-full rounded-lg border border-zinc-700 bg-zinc-800 px-4 py-3 text-zinc-100 placeholder:text-zinc-500 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-amber-500" />
                </div>

                <label class="flex items-center gap-2 text-sm text-zinc-400">
                    <input type="checkbox" name="remember"
                        class="h-4 w-4 rounded border-zinc-700 bg-zinc-800 text-amber-500 focus:ring-amber-500">
                    Lembrar-me
                </label>

                <button type="submit"
                    class="w-full rounded-lg bg-amber-500 px-4 py-3 font-semibold text-zinc-950 transition-colors duration-200 hover:bg-amber-400">
                    Entrar
                </button>
            </form>
        </div>
    </div>
</body>

</html>