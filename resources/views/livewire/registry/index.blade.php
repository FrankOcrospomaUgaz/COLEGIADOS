<div class="flex flex-1 flex-col gap-5">
    <section class="app-page-inline">
        <div class="app-page-inline-head items-center">
            <span class="app-page-inline-icon">
                <i class="fas fa-folder-open"></i>
            </span>
            <div>
                <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">{{ $module['title'] }}</h2>
            </div>
        </div>

        <div class="app-page-strip-breadcrumbs justify-end">
            <a href="{{ route('dashboard') }}" wire:navigate class="app-breadcrumb-link">Inicio</a>
            <i class="fas fa-angle-right text-xs text-slate-400"></i>
            <a href="{{ route('registries.catalog') }}" wire:navigate class="app-breadcrumb-link">Registros</a>
            <i class="fas fa-angle-right text-xs text-slate-400"></i>
            <span class="app-breadcrumb-current">{{ $module['title'] }}</span>
        </div>
    </section>

    <section class="app-crud-panel">
        <div class="app-crud-filter-grid">
            <div class="app-crud-search-block">
                <p class="app-crud-filter-label">Buscar</p>

                <label class="app-crud-search-shell">
                    <i class="fas fa-magnifying-glass app-crud-search-icon"></i>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        class="app-crud-search-input"
                        placeholder="Buscar por nombre, código, resolución o dato clave"
                    />
                </label>
            </div>

            <div class="app-crud-actions xl:justify-end">
                <a href="{{ route('registries.create', $module['slug']) }}" wire:navigate class="app-pill-action app-pill-action-primary">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo</span>
                </a>
                <button type="button" wire:click="$refresh" class="app-pill-action app-pill-action-warning">
                    <i class="fas fa-arrows-rotate"></i>
                    <span>Actualizar</span>
                </button>
                <a href="{{ route('registries.catalog') }}" wire:navigate class="app-pill-action app-pill-action-info">
                    <i class="fas fa-table-cells-large"></i>
                    <span>Módulos</span>
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="mt-5 rounded-[1.4rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if ($records->count())
            <div class="table-shell mt-5">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="table-head">
                            <tr>
                                <th class="px-4 py-4 text-center"></th>
                                @foreach ($module['columns'] as $column)
                                    <th class="px-5 py-4 text-left">{{ $column['label'] }}</th>
                                @endforeach
                                <th class="px-5 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr class="table-row">
                                    <td class="px-4 py-4 text-center">
                                        <a
                                            href="{{ route('registries.show', ['module' => $module['slug'], 'record' => $record->id]) }}"
                                            wire:navigate
                                            class="app-row-access"
                                            title="Abrir detalle"
                                        >
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </td>
                                    @foreach ($module['columns'] as $column)
                                        @php $value = data_get($record, $column['key']); @endphp
                                        <td class="px-5 py-4 align-top">
                                            @if ($value instanceof \Carbon\CarbonInterface)
                                                {{ $value->format('d/m/Y') }}
                                            @elseif (blank($value))
                                                <span class="text-slate-300">Sin dato</span>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ route('registries.show', ['module' => $module['slug'], 'record' => $record->id]) }}"
                                                wire:navigate
                                                class="app-circle-action app-circle-action-info"
                                                title="Ver detalle"
                                            >
                                                <i class="fas fa-list"></i>
                                            </a>
                                            <a
                                                href="{{ route('registries.edit', ['module' => $module['slug'], 'record' => $record->id]) }}"
                                                wire:navigate
                                                class="app-circle-action app-circle-action-edit"
                                                title="Editar registro"
                                            >
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button
                                                type="button"
                                                wire:click="confirmDelete({{ $record->id }})"
                                                class="app-circle-action app-circle-action-danger"
                                                title="Eliminar registro"
                                            >
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="app-table-tools">
                <p class="app-table-count">
                    Mostrando {{ $records->firstItem() }} a {{ $records->lastItem() }} de {{ $records->total() }} registros
                </p>
                <div class="flex items-center gap-2">
                    <button type="button" wire:click="$refresh" class="app-icon-tool" title="Actualizar tabla">
                        <i class="fas fa-arrows-rotate"></i>
                    </button>
                    <a href="{{ route('registries.catalog') }}" wire:navigate class="app-icon-tool" title="Ir a módulos">
                        <i class="fas fa-table-cells-large"></i>
                    </a>
                </div>
            </div>

            <div class="pt-4">
                {{ $records->links() }}
            </div>
        @else
            <div class="empty-state mt-5">
                <p class="mb-1 text-lg font-bold text-slate-800">No se encontraron registros</p>
                <p class="mb-5 text-sm text-slate-500">Prueba con otro término o crea el primer registro del módulo.</p>
                <a href="{{ route('registries.create', $module['slug']) }}" wire:navigate class="app-pill-action app-pill-action-primary">
                    <i class="fas fa-plus"></i>
                    <span>Crear registro</span>
                </a>
            </div>
        @endif
    </section>

    @if ($confirmingDeleteId)
        <div class="app-modal-backdrop">
            <div class="app-modal">
                <div class="app-modal-icon app-modal-icon-warning">
                    <i class="fas fa-exclamation"></i>
                </div>

                <h3 class="mt-8 text-4xl font-extrabold tracking-tight text-slate-900">Atencion</h3>
                <p class="mt-4 text-lg text-slate-600">
                    ¿Realmente desea enviar este registro a eliminados? La acción conserva la trazabilidad, pero deja el registro fuera de la operación diaria.
                </p>

                <div class="app-modal-actions">
                    <button type="button" wire:click="cancelDelete" class="app-btn app-btn-secondary">Cancelar</button>
                    <button type="button" wire:click="deleteConfirmed" class="app-pill-action app-pill-action-danger">
                        <i class="fas fa-trash"></i>
                        <span>Eliminar</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
