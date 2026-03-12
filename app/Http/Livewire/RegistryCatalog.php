<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use App\Services\RegistryModuleService;
use Livewire\Component;

class RegistryCatalog extends Component
{
    use UsesCurrentInstitution;

    public function render()
    {
        $institution = $this->currentInstitution();
        $service = app(RegistryModuleService::class);
        $categoryMeta = $service->categoryMeta();

        $groupedModules = collect($service->groupedModules())
            ->map(function ($modules) use ($service, $institution) {
                return collect($modules)->map(function ($module) use ($service, $institution) {
                    $module['count'] = $service->query($module['slug'], $institution)->count();
                    return $module;
                })->all();
            })
            ->all();

        return view('livewire.registry.catalog', compact('groupedModules', 'institution', 'categoryMeta'));
    }
}
