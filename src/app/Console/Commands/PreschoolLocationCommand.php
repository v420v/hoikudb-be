<?php

namespace App\Console\Commands;

use App\Models\Preschool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\PreschoolLocation;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Service\GoogleGeocodingService;

class PreschoolLocationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:preschool-location-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $preschools = Preschool::whereDoesntHave('preschoolLocation')->select('id', 'name', 'area_info')->get();

        $preschoolLocations = [];

        $googleGeocodingService = app(GoogleGeocodingService::class);

        foreach ($preschools as $preschool) {
            try {
                $address = '神奈川県 横浜市 ' . $preschool->area_info . ' ' . $preschool->name;

                $responseData = $googleGeocodingService->getCoordinates($address);

                if (!isset($responseData['status'])) {
                    $this->error('Invalid response format for ' . $preschool->name);
                    continue;
                }

                if ($responseData['status'] === 'ZERO_RESULTS') {
                    $this->warn('No results found for: ' . $address);
                    continue;
                }

                if ($responseData['status'] === 'OVER_QUERY_LIMIT') {
                    $this->error('API quota exceeded. Please try again later.');
                    break;
                }

                if ($responseData['status'] !== 'OK') {
                    $this->error('API Error: ' . $responseData['status'] . ' for ' . $preschool->name);
                    continue;
                }

                if (empty($responseData['results'])) {
                    $this->warn('No results in response for: ' . $address);
                    continue;
                }

                ['lat' => $lat, 'lng' => $lng] = $responseData['results'][0]['geometry']['location'];

                $preschoolLocations[] = [
                    'preschool_id' => $preschool->id,
                    'location' => DB::raw("ST_GeomFromText('POINT(" . $lng . ' ' . $lat . ")')"),
                    'address' => $responseData['results'][0]['formatted_address'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];

                $this->info($preschool->name . ' lat: ' . $lat . ' lng: ' . $lng);

                if (count($preschoolLocations) >= 500) {
                    PreschoolLocation::insert($preschoolLocations);
                    $preschoolLocations = [];
                }

                usleep(100000);
            } catch (Exception $e) {
                Log::error($e);
                continue;
            }
        }

        if (!empty($preschoolLocations)) {
            PreschoolLocation::insert($preschoolLocations);
        } else {
            $this->warn('No locations to insert.');
        }
    }
}
