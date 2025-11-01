<?php

namespace App\Service;

use App\Models\PreschoolStatsImportHistory;

class FetchPreschoolStatsImportHistoryListService
{
    const PER_PAGE = 30;

    public function __invoke(): array
    {
        $query = PreschoolStatsImportHistory::select('id', 'file_name', 'kind', 'target_date', 'created_at');

        // 件数を取得
        $totalCount = $query->count();

        // ページネーション付きでデータを取得
        $preschoolStatsImportHistories = $query->orderBy('id', 'desc')
            ->simplePaginate(self::PER_PAGE);

        return [
            'preschoolStatsImportHistories' => $preschoolStatsImportHistories,
            'total_count' => $totalCount,
        ];
    }
}
