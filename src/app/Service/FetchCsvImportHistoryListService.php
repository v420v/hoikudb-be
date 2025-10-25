<?php

namespace App\Service;

use App\Models\CsvImportHistory;
use Illuminate\Pagination\Paginator;

class FetchCsvImportHistoryListService
{
    const PER_PAGE = 30;

    public function __invoke(): array
    {
        $query = CsvImportHistory::select('id', 'file_name', 'kind', 'target_date', 'created_at');

        // 件数を取得
        $totalCount = $query->count();

        // ページネーション付きでデータを取得
        $csvImportHistories = $query->orderBy('id', 'desc')
            ->simplePaginate(self::PER_PAGE);

        return [
            'csvImportHistories' => $csvImportHistories,
            'total_count' => $totalCount,
        ];
    }
}
