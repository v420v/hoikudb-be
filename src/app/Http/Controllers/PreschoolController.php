<?php

namespace App\Http\Controllers;

use App\Models\CsvImportHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\PreschoolResource;
use App\Service\FetchPreschoolListService;
use App\Service\ImportPreschoolCsvService;
use App\Service\FetchCsvImportHistoryListService;
use App\Service\FetchImportHistoryService;
use App\Service\FetchPreschoolStatService;

class PreschoolController
{
    const PER_PAGE = 30;

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'name', 'building_code']);

        $result = app(FetchPreschoolListService::class)($filters);

        return view('preschool.index', [
            'preschools' => $result['preschools'],
            'total_count' => $result['total_count'],
        ]);
    }

    public function import()
    {
        $result = app(FetchCsvImportHistoryListService::class)();
        
        return view('preschool.import', [
            'csvImportHistories' => $result['csvImportHistories'],
            'total_count' => $result['total_count'],
        ]);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'target_month' => 'required|date_format:Y-m',
            'csv' => 'required|file|mimes:csv',
            'kind' => 'required|in:' . implode(',', [CsvImportHistory::KIND_WAITING, CsvImportHistory::KIND_CHILDREN, CsvImportHistory::KIND_ACCEPTANCE]),
        ]);

        $targetMonth = $request->input('target_month');
        $uploadedFile = $request->file('csv');
        $kind = $request->input('kind');

        app(ImportPreschoolCsvService::class)($uploadedFile, $kind, $targetMonth);

        return redirect()->route('preschool.import');
    }

    public function getStatsJson(Request $request)
    {
        $request->validate([
            'area' => 'required|string',
        ]);

        $targetDate = Carbon::now()->format('Y-m-d');
        $targetArea = $request->input('area');

        $preschools = app(FetchPreschoolStatService::class)($targetArea, $targetDate);

        $preschoolResources = PreschoolResource::collection($preschools);

        $geoJson = [
            "type" => "FeatureCollection",
            "crs" => [
                "type" => "name",
                "properties" => [
                    "name" => "EPSG:4326",
                ],
            ],
            "features" => $preschoolResources,
        ];

        return response()->json($geoJson);
    }

    public function importHistory($csvImportHistoryId)
    {
        $csvImportHistory = CsvImportHistory::find($csvImportHistoryId);

        $result = app(FetchImportHistoryService::class)($csvImportHistoryId);

        return view('preschool.import-history', [
            'preschoolMonthlyStats' => $result['preschoolMonthlyStats'],
            'total_count' => $result['total_count'],
            'csvImportHistory' => $csvImportHistory,
        ]);
    }
}
