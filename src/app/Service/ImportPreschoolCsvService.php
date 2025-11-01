<?php

namespace App\Service;

use App\Models\PreschoolStatsImportHistory;
use App\Models\Preschool;
use App\Models\PreschoolStat;
use App\Models\DataProviderFileConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ImportPreschoolCsvService
{
    const SKIP_LINES = [1, 2];

    const ENCODING_TO = 'UTF-8';

    private function getAgeCount($value): int
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        return 0;
    }

    public function __invoke(UploadedFile $uploadedFile, string $kind, string $targetDate, int $dataProviderFileConfigId, int $dataProviderId): void
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();
            $dataProviderFileConfig = DataProviderFileConfig::find($dataProviderFileConfigId);

            $preschoolStatsImportHistory = PreschoolStatsImportHistory::create([
                'data_provider_id' => $dataProviderId,
                'user_id' => $user->id,
                'target_date' => Carbon::parse($targetDate)->format('Y-m-d'),
                'kind' => $kind,
                'file_name' => $uploadedFile->getClientOriginalName(),
            ]);

            $file = new \SplFileObject($uploadedFile->getRealPath());

            switch ($dataProviderFileConfig->file_type) {
                case DataProviderFileConfig::FILE_TYPE_CSV:
                    $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
                    $file->setCsvControl($dataProviderFileConfig->delimiter, $dataProviderFileConfig->enclosure, "\\");
                    break;
                case DataProviderFileConfig::FILE_TYPE_PDF:
                    throw new \Exception('PDF file type is not supported');
                default:
                    throw new \Exception('Invalid file type');
            }

            $preschoolStats = [];

            foreach ($file as $i => $line) {
                $lineNumber = $i + 1;

                if ($file->eof()) {
                    break;
                }

                $encodedLine = array_map(function ($value) use ($dataProviderFileConfig) {
                    return is_string($value) ? mb_convert_encoding($value, self::ENCODING_TO, $dataProviderFileConfig->encoding) : $value;
                }, $line);

                if (in_array($lineNumber, self::SKIP_LINES)) {
                    continue;
                }

                $preschool = Preschool::where('name', $encodedLine[2])->where('building_code', $encodedLine[3])->first();

                if (!$preschool) {
                    $validator = Validator::make([
                        'area_info' => $encodedLine[0],
                        'name' => $encodedLine[2],
                        'building_code' => $encodedLine[3],
                    ], [
                        'area_info.required' => 'エリア情報が存在しません',
                        'name.required' => '園名が存在しません',
                        'building_code.required' => '建物コードが存在しません',
                    ]);

                    if ($validator->fails()) {
                        foreach ($validator->errors() as $error) {
                            Log::error($error->getMessage());
                        }
                        continue;
                    }

                    $preschool = Preschool::create([
                        'name' => $encodedLine[2],
                        'building_code' => $encodedLine[3],
                        'status' => Preschool::STATUS_ACTIVE,
                        'area_info' => $encodedLine[0],
                    ]);
                }

                $zeroYearOld = $this->getAgeCount($encodedLine[4]);
                $oneYearOld = $this->getAgeCount($encodedLine[5]);
                $twoYearOld = $this->getAgeCount($encodedLine[6]);
                $threeYearOld = $this->getAgeCount($encodedLine[7]);
                $fourYearOld = $this->getAgeCount($encodedLine[8]);
                $fiveYearOld = $this->getAgeCount($encodedLine[9]);

                $preschoolStats[] = [
                    'preschool_stats_import_history_id' => $preschoolStatsImportHistory->id,
                    'preschool_id' => $preschool->id,
                    'target_date' => Carbon::parse($targetDate)->format('Y-m-d'),
                    'kind' => $kind,
                    'zero_year_old' => $zeroYearOld,
                    'one_year_old' => $oneYearOld,
                    'two_year_old' => $twoYearOld,
                    'three_year_old' => $threeYearOld,
                    'four_year_old' => $fourYearOld,
                    'five_year_old' => $fiveYearOld,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }

            PreschoolStat::insert($preschoolStats);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
