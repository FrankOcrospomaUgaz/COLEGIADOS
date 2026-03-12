<div class="space-y-6">
    @if (session('status'))
        <div class="app-panel border border-emerald-200 bg-emerald-50/90 p-4 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="app-page-header">
        <div class="app-page-strip">
            <div class="app-page-strip-head items-center">
                <span class="app-page-strip-icon">
                    <i class="fas fa-key"></i>
                </span>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Cambiar contraseña</h2>
                </div>
            </div>

            <div class="app-page-strip-breadcrumbs justify-end">
                <a href="{{ route('dashboard') }}" wire:navigate class="app-breadcrumb-link">Inicio</a>
                <i class="fas fa-angle-right text-xs text-slate-400"></i>
                <a href="{{ route('profile') }}" wire:navigate class="app-breadcrumb-link">Perfil</a>
                <i class="fas fa-angle-right text-xs text-slate-400"></i>
                <span class="app-breadcrumb-current">Cambiar contraseña</span>
            </div>
        </div>
    </section>

    <section class="app-panel p-6 sm:p-8">
        <div class="mb-6">
            <h3 class="app-card-title">Seguridad de acceso</h3>
            <p class="app-card-subtitle">Actualiza tu clave y conserva la seguridad de tu cuenta institucional.</p>
        </div>

        <form wire:submit="save" class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="app-label">Contraseña actual</label>
                <input wire:model.blur="current_password" type="password" class="app-input" />
                @error('current_password') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="app-label">Nueva contraseña</label>
                <input wire:model.blur="password" type="password" class="app-input" />
                @error('password') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="app-label">Confirmar contraseña</label>
                <input wire:model.blur="password_confirmation" type="password" class="app-input" />
            </div>

            <div class="md:col-span-2 flex flex-wrap justify-end gap-3">
                <a href="{{ route('profile') }}" wire:navigate class="app-btn app-btn-secondary">Volver a perfil</a>
                <button type="submit" class="app-pill-action app-pill-action-success">
                    <i class="fas fa-floppy-disk"></i>
                    <span>Guardar contraseña</span>
                </button>
            </div>
        </form>
    </section>
</div>
