<?php

namespace App\Service;

use App\Models\CsvImportHistory;
use App\Models\PreschoolMonthlyStat;
use Illuminate\Pagination\Paginator;

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

        // 件数を取得
        $totalCount = $query->count();

        // ページネーション付きでデータを取得
        $preschoolMonthlyStats = $query->simplePaginate(self::PER_PAGE);

        return [
            'preschoolMonthlyStats' => $preschoolMonthlyStats,
            'total_count' => $totalCount,
        ];
    }
}