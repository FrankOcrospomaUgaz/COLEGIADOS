<div class="space-y-6">
    <section class="app-page-header">
        <div class="app-page-strip">
            <div class="app-page-strip-head items-center">
                <span class="app-page-strip-icon">
                    <i class="fas fa-file-circle-plus"></i>
                </span>
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">{{ $module['title'] }}</h2>
                    <span class="sr-only">
                        {{ $recordId ? 'Editar' : 'Nuevo' }} {{ \Illuminate\Support\Str::lower($module['singular']) }}
                    </span>
                </div>
            </div>

            <div class="app-page-strip-breadcrumbs justify-end">
                <a href="{{ route('dashboard') }}" wire:navigate class="app-breadcrumb-link">Inicio</a>
                <i class="fas fa-angle-right text-xs text-slate-400"></i>
                <a href="{{ route('registries.catalog') }}" wire:navigate class="app-breadcrumb-link">Registros</a>
                <i class="fas fa-angle-right text-xs text-slate-400"></i>
                <a href="{{ route('registries.index', $module['slug']) }}" wire:navigate class="app-breadcrumb-link">{{ $module['title'] }}</a>
            </div>
        </div>
    </section>

    <section class="app-panel brand-surface p-6 sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <span class="module-chip">{{ $module['category'] }}</span>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight md:text-[2.3rem]">Edición de formulario</h2>
                <p class="mt-3 max-w-3xl text-base text-slate-600">Completa los datos y conserva el estándar documental del módulo.</p>
            </div>
            <a href="{{ route('registries.index', $module['slug']) }}" wire:navigate class="app-pill-action app-pill-action-info">
                <i class="fas fa-table-list"></i>
                <span>Volver al listado</span>
            </a>
        </div>
    </section>

    @if (count($options['members'] ?? []) === 0 && $module['slug'] !== 'member-profiles')
        <div class="app-panel border border-amber-200 bg-amber-50/90 p-4 text-sm font-semibold text-amber-700">
            Primero debes registrar al menos una enfermera colegiada en el módulo base para poder enlazar este flujo.
        </div>
    @endif

    <form wire:submit="save" class="space-y-6">
        @foreach ($schema as $section)
            <section class="app-panel p-6 sm:p-8">
                <div class="app-form-section-head">
                    <span class="app-form-section-badge">
                        <i class="{{ $loop->first ? 'fas fa-link' : 'fas fa-pen-ruler' }}"></i>
                    </span>
                    <div>
                        <h3 class="app-card-title">{{ $section['title'] }}</h3>
                        <p class="app-form-section-copy">Completa la información requerida para este bloque del flujo.</p>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($section['fields'] as $field)
                        @if (($field['type'] ?? '') === 'repeater')
                            <div class="md:col-span-2">
                                <div class="mb-4 flex items-center justify-between">
                                    <label class="app-label mb-0">{{ $field['label'] }}</label>
                                    <button type="button" wire:click="addRow('{{ $field['name'] }}')" class="app-pill-action app-pill-action-warning">
                                        <i class="fas fa-plus"></i>
                                        <span>{{ $field['add_label'] }}</span>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    @foreach (($form[$field['name']] ?? []) as $index => $row)
                                        <article class="app-panel-muted p-4">
                                            <div class="mb-4 flex items-center justify-between">
                                                <p class="mb-0 text-sm font-semibold text-slate-800">Item {{ $index + 1 }}</p>
                                                <button type="button" wire:click="removeRow('{{ $field['name'] }}', {{ $index }})" class="text-sm font-semibold text-rose-600">
                                                    Quitar
                                                </button>
                                            </div>

                                            <div class="grid gap-4 md:grid-cols-2">
                                                @foreach ($field['fields'] as $subfield)
                                                    <div class="{{ !empty($subfield['full']) ? 'md:col-span-2' : '' }}">
                                                        <label class="app-label">{{ $subfield['label'] }}</label>

                                                        @if (($subfield['type'] ?? 'text') === 'select')
                                                            <select wire:model="form.{{ $field['name'] }}.{{ $index }}.{{ $subfield['name'] }}" class="app-select">
                                                                <option value="">Selecciona una opción</option>
                                                                @foreach (($options[$subfield['options']] ?? []) as $value => $label)
                                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                                @endforeach
                                                            </select>
                                                        @elseif (($subfield['type'] ?? 'text') === 'textarea')
                                                            <textarea wire:model="form.{{ $field['name'] }}.{{ $index }}.{{ $subfield['name'] }}" class="app-textarea"></textarea>
                                                        @else
                                                            <input wire:model="form.{{ $field['name'] }}.{{ $index }}.{{ $subfield['name'] }}" type="{{ $subfield['type'] ?? 'text' }}" class="app-input" />
                                                        @endif

                                                        @error("form.{$field['name']}.{$index}.{$subfield['name']}") <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                                                    </div>
                                                @endforeach
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="{{ !empty($field['full']) ? 'md:col-span-2' : '' }}">
                                <label class="app-label">{{ $field['label'] }}</label>

                                @if (($field['type'] ?? 'text') === 'select')
                                    <select wire:model="form.{{ $field['name'] }}" class="app-select">
                                        <option value="">Selecciona una opción</option>
                                        @foreach (($options[$field['options']] ?? []) as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                @elseif (($field['type'] ?? 'text') === 'textarea')
                                    <textarea wire:model="form.{{ $field['name'] }}" class="app-textarea"></textarea>
                                @else
                                    <input wire:model="form.{{ $field['name'] }}" type="{{ $field['type'] ?? 'text' }}" class="app-input" />
                                @endif

                                @error("form.{$field['name']}") <p class="app-help text-rose-600">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="flex flex-wrap justify-end gap-3">
            <a href="{{ route('registries.index', $module['slug']) }}" wire:navigate class="app-btn app-btn-secondary">Cancelar</a>
            <button type="submit" class="app-pill-action app-pill-action-success">
                <i class="fas fa-floppy-disk"></i>
                <span>Guardar registro</span>
            </button>
        </div>
    </form>
</div>
