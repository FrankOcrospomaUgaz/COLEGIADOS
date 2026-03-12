<?php

namespace App\Http\Livewire;

use App\Http\Livewire\Concerns\UsesCurrentInstitution;
use App\Models\Agreement;
use App\Models\DisciplinaryProcess;
use App\Models\InstitutionMembership;
use App\Models\MemberProfile;
use App\Models\Sponsorship;
use App\Services\RegistryModuleService;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Dashboard extends Component
{
    use UsesCurrentInstitution;

    public function render()
    {
        $institution = $this->currentInstitution();
        $service = app(RegistryModuleService::class);
        $categoryMeta = $service->categoryMeta();

        $moduleCards = collect($service->modules())
            ->map(function (array $module) use ($service, $institution) {
                return array_merge($module, [
                    'count' => $service->query($module['slug'], $institution)->count(),
                ]);
            })
            ->values();

        $categoryStats = collect($service->groupedModules())
            ->map(function (array $modules, string $category) use ($service, $institution, $categoryMeta) {
                $enriched = collect($modules)->map(function (array $module) use ($service, $institution) {
                    return array_merge($module, [
                        'count' => $service->query($module['slug'], $institution)->count(),
                    ]);
                });

                $recordsTotal = $enriched->sum('count');
                $modulesTotal = $enriched->count();
                $modulesActive = $enriched->where('count', '>', 0)->count();
                $meta = $categoryMeta[$category] ?? [];

                return [
                    'key' => $category,
                    'title' => $meta['title'] ?? $category,
                    'description' => $meta['description'] ?? $category,
                    'icon' => $meta['icon'] ?? 'fas fa-folder',
                    'records_total' => $recordsTotal,
                    'modules_total' => $modulesTotal,
                    'modules_active' => $modulesActive,
                    'coverage' => $modulesTotal > 0 ? (int) round(($modulesActive / $modulesTotal) * 100) : 0,
                ];
            })
            ->values();

        $totalRecords = $moduleCards->sum('count');
        $totalModules = $moduleCards->count();
        $activeModules = $moduleCards->where('count', '>', 0)->count();
        $modulesCoverage = $totalModules > 0 ? (int) round(($activeModules / $totalModules) * 100) : 0;
        $activeCategories = $categoryStats->where('records_total', '>', 0)->count();
        $totalCategories = $categoryStats->count();
        $categoryCoverage = $totalCategories > 0 ? (int) round(($activeCategories / $totalCategories) * 100) : 0;

        $categoryStats = $categoryStats
            ->map(function (array $item) use ($totalRecords) {
                return array_merge($item, [
                    'share' => $totalRecords > 0 ? (int) round(($item['records_total'] / $totalRecords) * 100) : 0,
                ]);
            })
            ->sortByDesc('records_total')
            ->all();

        $summary = [
            'members' => MemberProfile::query()->where('institution_id', $institution->getKey())->count(),
            'active_users' => InstitutionMembership::query()
                ->where('institution_id', $institution->getKey())
                ->where('status', 'active')
                ->count(),
            'active_agreements' => Agreement::query()
                ->where('institution_id', $institution->getKey())
                ->where('status', 'active')
                ->count(),
            'open_processes' => DisciplinaryProcess::query()
                ->where('institution_id', $institution->getKey())
                ->where('status', 'open')
                ->count(),
            'agreements_total' => Agreement::query()
                ->where('institution_id', $institution->getKey())
                ->count(),
            'processes_total' => DisciplinaryProcess::query()
                ->where('institution_id', $institution->getKey())
                ->count(),
            'sponsorships_total' => Sponsorship::query()
                ->where('institution_id', $institution->getKey())
                ->count(),
        ];

        $recentMembers = MemberProfile::query()
            ->where('institution_id', $institution->getKey())
            ->latest()
            ->take(5)
            ->get();

        $recentSponsorships = Sponsorship::query()
            ->where('institution_id', $institution->getKey())
            ->latest()
            ->take(4)
            ->get();

        $weekDayLabels = [1 => 'Lun', 2 => 'Mar', 3 => 'Mié', 4 => 'Jue', 5 => 'Vie', 6 => 'Sáb', 7 => 'Dom'];

        $activityWindow = collect(range(6, 0))
            ->map(function (int $offset) use ($institution, $weekDayLabels) {
                $day = CarbonImmutable::now()->subDays($offset);
                $date = $day->toDateString();

                $count = MemberProfile::query()
                    ->where('institution_id', $institution->getKey())
                    ->whereDate('created_at', $date)
                    ->count()
                    + Agreement::query()
                        ->where('institution_id', $institution->getKey())
                        ->whereDate('created_at', $date)
                        ->count()
                    + Sponsorship::query()
                        ->where('institution_id', $institution->getKey())
                        ->whereDate('created_at', $date)
                        ->count()
                    + DisciplinaryProcess::query()
                        ->where('institution_id', $institution->getKey())
                        ->whereDate('created_at', $date)
                        ->count();

                return [
                    'label' => $weekDayLabels[$day->dayOfWeekIso] ?? $day->format('D'),
                    'date' => $day->format('d/m'),
                    'count' => $count,
                ];
            });

        $activityMax = max(1, (int) $activityWindow->max('count'));
        $activityWindow = $activityWindow
            ->map(fn (array $item) => array_merge($item, [
                'height' => max(18, (int) round(($item['count'] / $activityMax) * 100)),
            ]))
            ->all();

        return view('livewire.dashboard', compact(
            'institution',
            'summary',
            'totalRecords',
            'totalModules',
            'activeModules',
            'modulesCoverage',
            'activeCategories',
            'totalCategories',
            'categoryCoverage',
            'categoryStats',
            'activityWindow',
            'recentMembers',
            'recentSponsorships'
        ));
    }
}
