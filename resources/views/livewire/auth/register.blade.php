<main class="mx-auto max-w-[1600px] px-4 pb-12 pt-6 sm:px-6 xl:px-8">
    <section class="grid min-h-[calc(100vh-180px)] gap-6 lg:grid-cols-[1.05fr,0.95fr]">
        <div class="app-panel brand-surface p-6 sm:p-8">
            <span class="module-chip">Alta institucional</span>
            <h1 class="mt-4 text-4xl font-extrabold tracking-tight">Crea la primera institución y su usuario administrador</h1>
            <p class="mt-3 max-w-2xl text-base text-slate-600">
                El registro inicial crea el tenant, su plan base, la membresía del usuario y deja habilitados
                todos los módulos para empezar a cargar información real.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                <article class="app-panel-muted px-4 py-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Tenant</p>
                    <p class="mb-0 text-sm font-semibold text-slate-800">Institución aislada y lista para SaaS</p>
                </article>
                <article class="app-panel-muted px-4 py-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Seguridad</p>
                    <p class="mb-0 text-sm font-semibold text-slate-800">Usuario owner y switch de institución</p>
                </article>
                <article class="app-panel-muted px-4 py-4">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Operación</p>
                    <p class="mb-0 text-sm font-semibold text-slate-800">17 módulos enlazados por base de datos</p>
                </article>
            </div>
        </div>

        <div class="app-panel p-6 sm:p-8">
            <div class="mx-auto w-full max-w-xl">
                <h2 class="text-3xl font-extrabold tracking-tight">Datos de acceso</h2>
                <p class="mt-2 text-sm text-slate-500">Usa información real de la institución y del usuario owner.</p>

                <form wire:submit="register" class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="md:col-span-2">
                        <label class="app-label">Nombre de la institución</label>
                        <input wire:model.blur="institution_name" type="text" class="app-input" />
                        @error('institution_name') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="app-label">Slug institucional</label>
                        <input wire:model.blur="institution_slug" type="text" class="app-input" placeholder="consejo-regional-lima" />
                        @error('institution_slug') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="app-label">Nombre del administrador</label>
                        <input wire:model.blur="name" type="text" class="app-input" />
                        @error('name') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="app-label">Correo del administrador</label>
                        <input wire:model.blur="email" type="email" class="app-input" />
                        @error('email') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="app-label">Contraseña</label>
                        <input wire:model.blur="password" type="password" class="app-input" />
                        @error('password') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="app-label">Confirmar contraseña</label>
                        <input wire:model.blur="password_confirmation" type="password" class="app-input" />
                    </div>

                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="app-btn app-btn-primary">Crear institución</button>
                    </div>
                </form>

                <p class="mt-6 text-sm text-slate-500">
                    ¿Ya tienes acceso?
                    <a href="{{ route('login') }}" class="font-semibold text-teal-700">Ingresa aquí</a>.
                </p>
            </div>
        </div>
    </section>
</main>
