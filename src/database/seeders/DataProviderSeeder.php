<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DataProvider;

class DataProviderSeeder extends Seeder
{
    public function run()
    {
        $dataProviders = [
            [
                'name' => '横浜市',
            ],
        ];

        foreach ($dataProviders as $dataProvider) {
            DataProvider::create($dataProvider);
        }
    }
}
