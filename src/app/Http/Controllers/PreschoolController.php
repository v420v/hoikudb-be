<?php

namespace App\Http\Controllers;

use App\Models\CsvImportHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Resources\PreschoolResource;
use App\Service\FetchPreschoolListService;
use App\Service\ImportPreschoolCsvService;
use App\Service\FetchPreschoolStatsImportHistoryListService;
use App\Service\FetchPreschoolStatsService;
use App\Service\FetchPreschoolStatService;
use App\Models\DataProvider;
use App\Models\DataProviderFileConfig;
use App\Models\PreschoolStatsImportHistory;

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
        $result = app(FetchPreschoolStatsImportHistoryListService::class)();

        $dataProviders = DataProvider::orderBy('name')->get();
        $dataProviderFileConfigs = DataProviderFileConfig::orderBy('display_name')->get();

        return view('preschool.import', [
            'preschoolStatsImportHistories' => $result['preschoolStatsImportHistories'],
            'total_count' => $result['total_count'],
            'dataProviders' => $dataProviders,
            'dataProviderFileConfigs' => $dataProviderFileConfigs,
        ]);
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'target_date' => 'required|date_format:Y-m-d',
            'csv' => 'required|file|mimes:csv',
            'kind' => 'required|in:' . implode(',', [PreschoolStatsImportHistory::KIND_WAITING, PreschoolStatsImportHistory::KIND_CHILDREN, PreschoolStatsImportHistory::KIND_ACCEPTANCE]),
            'data_provider_id' => 'required|exists:data_providers,id',
            'data_provider_file_config_id' => 'required|exists:data_provider_file_configs,id',
        ]);

        $targetDate = $request->input('target_date');
        $uploadedFile = $request->file('csv');
        $kind = $request->input('kind');

        $dataProviderId = (int)$request->input('data_provider_id');
        $dataProviderFileConfigId = (int)$request->input('data_provider_file_config_id');

        app(ImportPreschoolCsvService::class)($uploadedFile, $kind, $targetDate, $dataProviderFileConfigId, $dataProviderId);

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

    public function importHistory($preschoolStatsImportHistoryId)
    {
        $preschoolStatsImportHistory = PreschoolStatsImportHistory::find($preschoolStatsImportHistoryId);

        $result = app(FetchPreschoolStatsService::class)($preschoolStatsImportHistory->id);

        return view('preschool.import-history', [
            'preschoolStats' => $result['preschoolStats'],
            'total_count' => $result['total_count'],
            'preschoolStatsImportHistory' => $preschoolStatsImportHistory,
        ]);
    }
}
