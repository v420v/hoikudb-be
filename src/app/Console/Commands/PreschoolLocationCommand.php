<?php

namespace App\Console\Commands;

use App\Models\Preschool;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Models\PreschoolLocation;
use Exception;
use Illuminate\Support\Facades\Log;

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
        foreach ($preschools as $preschool) {
            try {
                $client = new Client();

                $requestUrl = config('google-geocoder.api_url') . '?address=神奈川県 横浜市 ' . $preschool->area_info . ' ' . $preschool->name . '&key=' . config('google-geocoder.api_key') . '&language=ja';

                $response = $client->get($requestUrl);

                if ($response->getStatusCode() !== 200) {
                    $this->error('Failed to get location for ' . $preschool->name);
                    continue;
                }

                $responseData = json_decode($response->getBody(), true);

                if ($responseData['status'] !== 'OK') {
                    $this->error('Failed to get location for ' . $preschool->name);
                    continue;
                }

                ['lat' => $lat, 'lng' => $lng] = $responseData['results'][0]['geometry']['location'];

                $address = $responseData['results'][0]['formatted_address'];

                $preschoolLocations[] = [
                    'preschool_id' => $preschool->id,
                    'location' => DB::raw("ST_GeomFromText('POINT(" . $lng . ' ' . $lat . ")')"),
                    'address' => $address,
                ];
            } catch (Exception $e) {
                Log::error($e);
            }
        }

        PreschoolLocation::insert($preschoolLocations);
    }
}
