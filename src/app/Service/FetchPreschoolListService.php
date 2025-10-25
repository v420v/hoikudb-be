<?php

namespace App\Service;

use App\Models\Preschool;

class FetchPreschoolListService
{
    const PER_PAGE = 30;

    public function __invoke(array $filters = []): array
    {
        $query = Preschool::select('id', 'status', 'name', 'building_code', 'created_at')
            ->whereIn('status', [
                Preschool::STATUS_ACTIVE,
                Preschool::STATUS_INACTIVE,
            ])
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['name']), function ($query) use ($filters) {
                $query->where('name', 'LIKE', '%' . $filters['name'] . '%');
            })
            ->when(!empty($filters['building_code']), function ($query) use ($filters) {
                $query->where('building_code', 'LIKE', '%' . $filters['building_code'] . '%');
            });

        $totalCount = $query->count();

        $preschools = $query->orderBy('id', 'desc')
            ->simplePaginate(self::PER_PAGE)
            ->appends($filters);

        return [
            'preschools' => $preschools,
            'total_count' => $totalCount,
        ];
    }
}
