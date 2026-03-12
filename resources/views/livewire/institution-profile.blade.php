<div class="space-y-6">
    @if (session('status'))
        <div class="app-panel border border-emerald-200 bg-emerald-50/90 p-4 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="grid gap-6 xl:grid-cols-[1fr,0.9fr]">
        <div class="app-panel p-6">
            <div class="mb-5">
                <h2 class="app-card-title">Configuración institucional</h2>
                <p class="app-card-subtitle">Identidad del tenant, datos de contacto y colores de marca.</p>
            </div>

            <form wire:submit="saveInstitution" class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="app-label">Nombre comercial</label>
                    <input wire:model="name" type="text" class="app-input" />
                    @error('name') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="app-label">Razón social</label>
                    <input wire:model="legal_name" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">RUC / ID fiscal</label>
                    <input wire:model="tax_id" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Correo institucional</label>
                    <input wire:model="email" type="email" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Teléfono</label>
                    <input wire:model="phone" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Sitio web</label>
                    <input wire:model="website" type="url" class="app-input" />
                </div>
                <div class="md:col-span-2">
                    <label class="app-label">Dirección</label>
                    <input wire:model="address" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Ciudad</label>
                    <input wire:model="city" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Región / Estado</label>
                    <input wire:model="state" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">País</label>
                    <input wire:model="country" type="text" class="app-input" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="app-label">Color primario</label>
                        <input wire:model="primary_color" type="color" class="app-input h-12 p-2" />
                    </div>
                    <div>
                        <label class="app-label">Color secundario</label>
                        <input wire:model="secondary_color" type="color" class="app-input h-12 p-2" />
                    </div>
                </div>
                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="app-btn app-btn-primary">Guardar institución</button>
                </div>
            </form>
        </div>

        <div class="space-y-6">
            <section class="app-panel p-6">
                <h3 class="app-card-title">Suscripción actual</h3>
                <p class="app-card-subtitle mb-4">Base SaaS preparada para planes y límites por tenant.</p>

                <div class="brand-hero rounded-[2rem] p-5 text-white">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-white/70">Plan</p>
                    <h4 class="mb-2 text-2xl font-extrabold text-white">
                        {{ $institution->currentSubscription?->plan?->name ?? 'Starter manual' }}
                    </h4>
                    <p class="mb-0 text-sm text-white/80">
                        Estado: {{ ucfirst($institution->currentSubscription?->status ?? $institution->status) }}
                    </p>
                </div>
            </section>

            <section class="app-panel p-6">
                <h3 class="app-card-title">Equipo del tenant</h3>
                <p class="app-card-subtitle mb-4">Usuarios vinculados a la institución activa.</p>

                <div class="space-y-3">
                    @foreach ($memberships as $membership)
                        <article class="app-panel-muted flex items-center justify-between px-4 py-4">
                            <div>
                                <p class="mb-0 text-sm font-semibold text-slate-800">{{ $membership->user->name }}</p>
                                <p class="mb-0 text-xs text-slate-500">{{ strtoupper($membership->role) }} - {{ ucfirst($membership->status) }}</p>
                            </div>
                            @if ($membership->is_primary)
                                <span class="status-pill">Principal</span>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</div>
