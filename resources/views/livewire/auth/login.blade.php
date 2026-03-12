<main class="mx-auto max-w-[1600px] px-4 pb-12 pt-6 sm:px-6 xl:px-8">
    <section class="grid min-h-[calc(100vh-180px)] gap-6 lg:grid-cols-[1fr,0.95fr]">
        <div class="brand-hero hidden rounded-[2rem] p-10 text-white shadow-soft-xl lg:block">
            <span class="module-chip border-white/20 bg-white/10 text-white/80">COLEGIADOS</span>
            <h1 class="mt-6 text-5xl font-extrabold text-white">Gestión institucional con enfoque regional.</h1>
            <p class="mt-5 max-w-xl text-lg text-white/80">
                Unifica padrones, convenios, gobierno institucional, ética, sanciones y bienestar sobre una base SaaS
                multi-tenant preparada para PostgreSQL.
            </p>
        </div>

        <div class="app-panel flex items-center p-6 sm:p-8">
            <div class="mx-auto w-full max-w-lg">
                <span class="module-chip">Ingreso</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight">Accede a tu institución</h2>
                <p class="mt-2 text-sm text-slate-500">Ingresa con tu cuenta administrativa para continuar.</p>

                @if (Session::has('status'))
                    <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ Session::get('status') }}
                    </div>
                @endif

                <form wire:submit="login" class="mt-6 space-y-4">
                    <div>
                        <label class="app-label">Correo</label>
                        <input wire:model.blur="email" type="email" class="app-input" placeholder="admin@institución.pe" />
                        @error('email') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="app-label">Contraseña</label>
                        <input wire:model.blur="password" type="password" class="app-input" placeholder="••••••••" />
                        @error('password') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <label class="inline-flex items-center gap-3 text-sm font-semibold text-slate-600">
                        <input wire:model.live="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400" />
                        Mantener sesión iniciada
                    </label>

                    <button type="submit" class="app-btn app-btn-primary w-full justify-center">Ingresar</button>
                </form>

                <p class="mt-6 text-sm text-slate-500">
                    ¿Olvidaste tu contraseña?
                    <a href="{{ route('forgot-password') }}" class="font-semibold text-teal-700">Recupérala aquí</a>.
                </p>
                <p class="mt-2 text-sm text-slate-500">
                    ¿Aún no tienes institución?
                    <a href="{{ route('register') }}" class="font-semibold text-teal-700">Crea el tenant inicial</a>.
                </p>
            </div>
        </div>
    </section>
</main>
