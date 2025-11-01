<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataProviderFileConfig;
use App\Models\DataProvider;

class DataProviderFileConfigSeeder extends Seeder
{
    public function run()
    {
        $dataProviderFileConfigs = [
            [
                'data_provider_id' => DataProvider::where('name', '横浜市')->first()->id,
                'display_name' => '横浜市保育園データ',
                'file_type' => 'csv',
                'new_line' => 'crlf',
                'encoding' => 'sjis',
                'delimiter' => ',',
                'enclosure' => '"',
            ],
        ];

        foreach ($dataProviderFileConfigs as $dataProviderFileConfig) {
            DataProviderFileConfig::create($dataProviderFileConfig);
        }
    }
}
