<?php

namespace App\Service;

use App\Models\CsvImportHistory;
use App\Models\Preschool;
use App\Models\PreschoolMonthlyStat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;

class ImportPreschoolCsvService
{
    const SKIP_LINES = [1, 2];

    private function getAgeCount($value): int
    {
        if (is_numeric($value)) {
            return (int)$value;
        }
        return 0;
    }

    public function __invoke(UploadedFile $uploadedFile, string $kind, string $targetMonth): void
    {
        $user = Auth::user();

        $csvImportHistory = CsvImportHistory::create([
            'user_id' => $user->id,
            'target_date' => Carbon::parse($targetMonth)->format('Y-m-d'),
            'file_name' => $uploadedFile->getClientOriginalName(),
            'kind' => $kind,
        ]);

        $file = new \SplFileObject($uploadedFile->getRealPath());
        $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);
        $file->setCsvControl(',', '"', '\\');

        $preschoolMonthlyStats = [];

        foreach ($file as $i => $line) {
            $lineNumber = $i + 1;

            if ($file->eof()) {
                break;
            }

            $utf8Line = array_map(function ($value) {
                return is_string($value) ? mb_convert_encoding($value, 'UTF-8', 'SJIS-win') : $value;
            }, $line);

            if (in_array($lineNumber, self::SKIP_LINES)) {
                continue;
            }

            $preschool = Preschool::where('name', $utf8Line[2])->where('building_code', $utf8Line[3])->first();

            if (!$preschool) {
                $preschool = Preschool::create([
                    'name' => $utf8Line[2],
                    'building_code' => $utf8Line[3],
                    'status' => Preschool::STATUS_ACTIVE,
                    'area_info' => $utf8Line[0],
                ]);
            }

            $zeroYearOld = $this->getAgeCount($utf8Line[4]);
            $oneYearOld = $this->getAgeCount($utf8Line[5]);
            $twoYearOld = $this->getAgeCount($utf8Line[6]);
            $threeYearOld = $this->getAgeCount($utf8Line[7]);
            $fourYearOld = $this->getAgeCount($utf8Line[8]);
            $fiveYearOld = $this->getAgeCount($utf8Line[9]);

            $preschoolMonthlyStats[] = [
                'csv_import_history_id' => $csvImportHistory->id,
                'preschool_id' => $preschool->id,
                'target_date' => Carbon::parse($targetMonth)->format('Y-m-d'),
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

        PreschoolMonthlyStat::insert($preschoolMonthlyStats);
    }
}