<?php

namespace App\Service;

use App\Models\Preschool;
use Illuminate\Database\Eloquent\Collection;
use App\Models\CsvImportHistory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FetchPreschoolStatService
{
    public function __invoke(string $targetArea, string $targetDate): Collection
    {
        $latestImportHistoryIds = CsvImportHistory::selectRaw('MAX(id) as id, kind')
            ->whereIn('kind', [
                CsvImportHistory::KIND_WAITING,
                CsvImportHistory::KIND_ACCEPTANCE,
                CsvImportHistory::KIND_CHILDREN
            ])
            ->whereMonth('target_date', '=', Carbon::parse($targetDate)->month)
            ->whereYear('target_date', '=', Carbon::parse($targetDate)->year)
            ->groupBy('kind')
            ->get()
            ->pluck('id')
            ->toArray();

        $preschools = Preschool::select('id', 'name', 'area_info')
            ->withWhereHas('preschoolMonthlyStats', function($query) use ($latestImportHistoryIds) {
                $query->select('preschool_id', 'kind', 'zero_year_old', 'one_year_old', 'two_year_old', 'three_year_old', 'four_year_old', 'five_year_old', 'target_date')
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
