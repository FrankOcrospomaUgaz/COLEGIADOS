<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use App\Services\RegistryModuleService;
use Livewire\Component;

class RegistryShow extends Component
{
    use UsesCurrentInstitution;

    public string $moduleSlug;
    public int $recordId;

    public function mount(string $module, int $record): void
    {
        $this->moduleSlug = $module;
        $this->recordId = $record;
    }

    public function render()
    {
        $service = app(RegistryModuleService::class);
        $institution = $this->currentInstitution();
        $module = $service->module($this->moduleSlug);
        $record = $service->query($this->moduleSlug, $institution)->findOrFail($this->recordId);
        $details = $service->detailRows($this->moduleSlug, $record);
        $collections = $service->relatedCollections($this->moduleSlug, $record);

        return view('livewire.registry.show', compact('module', 'record', 'details', 'collections', 'institution'));
    }
}
