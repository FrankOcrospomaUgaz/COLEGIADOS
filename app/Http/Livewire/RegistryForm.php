<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use App\Services\RegistryModuleService;
use Livewire\Component;

class RegistryForm extends Component
{
    use UsesCurrentInstitution;

    public string $moduleSlug;
    public ?int $recordId = null;
    public array $form = [];

    public function mount(string $module, ?int $record = null): void
    {
        $this->moduleSlug = $module;
        $this->recordId = $record;

        $service = app(RegistryModuleService::class);
        $service->module($module);

        if ($record) {
            $model = $service->query($module, $this->currentInstitution())->findOrFail($record);
            $this->form = $service->formState($module, $model);
            return;
        }

        $this->form = $service->formState($module);
    }

    public function addRow(string $name): void
    {
        $this->form[$name][] = app(RegistryModuleService::class)->blankRow($name);
    }

    public function removeRow(string $name, int $index): void
    {
        unset($this->form[$name][$index]);
        $this->form[$name] = array_values($this->form[$name]);
    }

    public function save()
    {
        $service = app(RegistryModuleService::class);
        $this->validate($service->rules($this->moduleSlug, $this->currentInstitution(), $this->recordId));

        $record = $service->save($this->moduleSlug, $this->currentInstitution(), auth()->user(), $this->form, $this->recordId);

        session()->flash('status', 'Registro guardado correctamente.');

        return redirect()->route('registries.show', ['module' => $this->moduleSlug, 'record' => $record->getKey()]);
    }

    public function render()
    {
        $service = app(RegistryModuleService::class);
        $institution = $this->currentInstitution();
        $module = $service->module($this->moduleSlug);
        $schema = $service->formSchema($this->moduleSlug);
        $options = $service->options($institution);

        return view('livewire.registry.form', compact('module', 'schema', 'options', 'institution'));
    }
}
