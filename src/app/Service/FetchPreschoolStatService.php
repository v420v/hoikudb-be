<?php

namespace App\Service;

use App\Models\Preschool;
use Illuminate\Database\Eloquent\Collection;
use App\Models\PreschoolStatsImportHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FetchPreschoolStatService
{
    public function __invoke(string $targetArea): Collection
    {
        $importHistorys = PreschoolStatsImportHistory::selectRaw('MAX(id) as id, kind')
            ->whereIn('kind', [
                PreschoolStatsImportHistory::KIND_WAITING,
                PreschoolStatsImportHistory::KIND_ACCEPTANCE,
                PreschoolStatsImportHistory::KIND_CHILDREN
            ])
            ->groupBy('kind')
            ->get();

        $latestImportHistory = $importHistorys->sortByDesc('target_date')->first();
        $importHistorys = $importHistorys->filter(function($importHistory) use ($latestImportHistory) {
            return Carbon::parse($importHistory->target_date)->format('Y-m') == Carbon::parse($latestImportHistory->target_date)->format('Y-m');
        });

        $latestImportHistoryIds = $importHistorys->pluck('id')->toArray();

        $preschools = Preschool::select('id', 'name', 'area_info')
            ->withWhereHas('preschoolStats', function($query) use ($latestImportHistoryIds) {
                $query->select('preschool_id', 'kind', 'zero_year_old', 'one_year_old', 'two_year_old', 'three_year_old', 'four_year_old', 'five_year_old', 'target_date')
                    ->whereIn('preschool_stats_import_history_id', $latestImportHistoryIds);
            })
            ->withWhereHas('preschoolLocation', function($query) {
                $query->select('id', 'preschool_id', DB::raw('ST_X(location) as longitude'), DB::raw('ST_Y(location) as latitude'), 'address')
                    ->whereNotNull('location');
            })
            ->where('area_info', $targetArea)
            ->orderBy('id', 'asc')
            ->get();

        return $preschools;
    }
}
