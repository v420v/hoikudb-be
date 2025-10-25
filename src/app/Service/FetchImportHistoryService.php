<?php

namespace App\Service;

use App\Models\PreschoolMonthlyStat;

class FetchImportHistoryService
{
    const PER_PAGE = 30;

    public function __invoke(int $csvImportHistoryId): array
    {
        $query = PreschoolMonthlyStat::select('preschool_id', 'zero_year_old', 'one_year_old', 'two_year_old', 'three_year_old', 'four_year_old', 'five_year_old')
            ->with(['preschool' => function($query) {
                $query->select('id', 'name');
            }])
            ->where('csv_import_history_id', $csvImportHistoryId);

        $totalCount = $query->count();

        $preschoolMonthlyStats = $query->simplePaginate(self::PER_PAGE);

        return [
            'preschoolMonthlyStats' => $preschoolMonthlyStats,
            'total_count' => $totalCount,
        ];
    }
}