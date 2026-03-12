<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use App\Services\RegistryModuleService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class RegistryIndex extends Component
{
    use UsesCurrentInstitution;
    use WithPagination;

    public string $moduleSlug;
    public ?int $confirmingDeleteId = null;

    #[Url(as: 'q')]
    public string $search = '';

    public function mount(string $module): void
    {
        $this->moduleSlug = $module;
        app(RegistryModuleService::class)->module($module);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteConfirmed(): void
    {
        if (! $this->confirmingDeleteId) {
            return;
        }

        app(RegistryModuleService::class)->delete($this->moduleSlug, $this->currentInstitution(), $this->confirmingDeleteId);
        $this->confirmingDeleteId = null;
        session()->flash('status', 'Registro eliminado.');
    }

    public function render()
    {
        $service = app(RegistryModuleService::class);
        $institution = $this->currentInstitution();
        $module = $service->module($this->moduleSlug);

        $records = $service->applySearch($this->moduleSlug, $service->query($this->moduleSlug, $institution), $this->search)
            ->latest()
            ->paginate(12);

        return view('livewire.registry.index', compact('module', 'records', 'institution'));
    }
}
