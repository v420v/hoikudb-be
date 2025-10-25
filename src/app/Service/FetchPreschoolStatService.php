<?php

namespace App\Service;

use App\Models\Preschool;
use Illuminate\Database\Eloquent\Collection;
use App\Models\CsvImportHistory;
use Illuminate\Support\Facades\DB;

class FetchPreschoolStatService
{
    public function __invoke(string $targetArea, int $targetYear, int $targetMonth): Collection
    {
        $latestImportHistoryIds = CsvImportHistory::selectRaw('MAX(id) as id, kind')
            ->whereIn('kind', [
                CsvImportHistory::KIND_WAITING,
                CsvImportHistory::KIND_ACCEPTANCE,
                CsvImportHistory::KIND_CHILDREN
            ])
            ->whereYear('created_at', '=', $targetYear)
            ->whereMonth('created_at', '=', $targetMonth)
            ->groupBy('kind')
            ->get()
            ->pluck('id')
            ->toArray();

        $preschools = Preschool::select('id', 'name', 'area_info')
            ->withWhereHas('preschoolMonthlyStats', function($query) use ($targetMonth, $targetYear, $latestImportHistoryIds) {
                $query->select('preschool_id', 'kind', 'zero_year_old', 'one_year_old', 'two_year_old', 'three_year_old', 'four_year_old', 'five_year_old')
                    ->whereIn('csv_import_history_id', $latestImportHistoryIds);
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
