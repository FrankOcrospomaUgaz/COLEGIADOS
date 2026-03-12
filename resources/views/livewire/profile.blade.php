<div class="space-y-6">
    @if (session('status'))
        <div class="app-panel border border-emerald-200 bg-emerald-50/90 p-4 text-sm font-semibold text-emerald-700">
            {{ session('status') }}
        </div>
    @endif

    <section class="grid gap-6 xl:grid-cols-[1fr,0.9fr]">
        <div class="app-panel p-6">
            <div class="mb-5">
                <h2 class="app-card-title">Perfil del usuario</h2>
                <p class="app-card-subtitle">Actualiza tus datos y selecciona la institución operativa.</p>
            </div>

            <form wire:submit="saveProfile" class="grid gap-4 md:grid-cols-2">
                <div>
                    <label class="app-label">Nombre</label>
                    <input wire:model="name" type="text" class="app-input" />
                    @error('name') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="app-label">Correo</label>
                    <input wire:model="email" type="email" class="app-input" />
                    @error('email') <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="app-label">Celular</label>
                    <input wire:model="phone" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Cargo</label>
                    <input wire:model="job_title" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Ubicación</label>
                    <input wire:model="location" type="text" class="app-input" />
                </div>
                <div>
                    <label class="app-label">Institución activa</label>
                    <select wire:model="current_institution_id" class="app-select">
                        @foreach ($memberships as $membership)
                            <option value="{{ $membership->institution_id }}">{{ $membership->institution->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="app-label">Acerca de</label>
                    <textarea wire:model="about" class="app-textarea"></textarea>
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="app-btn app-btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>

        <div class="space-y-6">
            <section class="app-panel p-6">
                <h3 class="app-card-title">Institución en uso</h3>
                <p class="app-card-subtitle mb-5">Contexto actual de trabajo dentro del tenant.</p>

                <div class="app-panel-muted px-5 py-5">
                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Institución</p>
                    <p class="mb-4 text-xl font-bold text-slate-900">{{ $institution->name }}</p>

                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">País</p>
                    <p class="mb-4 text-sm font-semibold text-slate-800">{{ $institution->country ?: 'Sin dato' }}</p>

                    <p class="mb-2 text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Estado de tenant</p>
                    <span class="status-pill">{{ ucfirst($institution->status) }}</span>
                </div>
            </section>

            <section class="app-panel p-6">
                <h3 class="app-card-title">Instituciones disponibles</h3>
                <p class="app-card-subtitle mb-4">Si tu usuario pertenece a más de una entidad, puedes alternar aquí.</p>

                <div class="space-y-3">
                    @foreach ($memberships as $membership)
                        <article class="app-panel-muted flex items-center justify-between px-4 py-4">
                            <div>
                                <p class="mb-0 text-sm font-semibold text-slate-800">{{ $membership->institution->name }}</p>
                                <p class="mb-0 text-xs text-slate-500">{{ strtoupper($membership->role) }} - {{ ucfirst($membership->status) }}</p>
                            </div>
                            @if ((int) $current_institution_id === $membership->institution_id)
                                <span class="status-pill">Activa</span>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        </div>
    </section>
</div>
