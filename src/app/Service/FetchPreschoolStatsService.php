<?php

namespace App\Service;

use App\Models\PreschoolStat;

class FetchPreschoolStatsService
{
    const PER_PAGE = 30;

    public function __invoke(int $preschoolStatsImportHistoryId): array
    {
        $query = PreschoolStat::select('preschool_id', 'zero_year_old', 'one_year_old', 'two_year_old', 'three_year_old', 'four_year_old', 'five_year_old')
            ->with(['preschool' => function($query) {
                $query->select('id', 'name');
            }])
            ->where('preschool_stats_import_history_id', $preschoolStatsImportHistoryId);

        $totalCount = $query->count();

        $preschoolStats = $query->simplePaginate(self::PER_PAGE);

        return [
            'preschoolStats' => $preschoolStats,
            'total_count' => $totalCount,
        ];
    }
}