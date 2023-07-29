<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Item;
use App\Models\Street;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesStreetsItemsTagsSeeder extends Seeder
{
    public function run(): void
    {

        $tags = [
            [
                'name'        => 'Fognatura Bianca',
                'type'        => 'Recapito',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Fognatura Nera',
                'type'        => 'Recapito',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Fognatura Mista',
                'type'        => 'Recapito',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Caditoia',
                'type'        => 'Tipologia',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Bocca di Lupo',
                'type'        => 'Tipologia',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Griglia',
                'type'        => 'Tipologia',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Funzionante',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Rotta',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Bloccata',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Cemento',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Radici',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Non Scarica',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Fondo Rotto',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
            [
                'name'        => 'Macchina Sopra',
                'type'        => 'Stato',
                'description' => '',
                'domain'       => 'item',
            ],
        ];

        $newtag = collect();

        foreach ($tags as $tag) {
            $newtag->push(Tag::create([
                'name'          => $tag['name'],
                'type'          => $tag['type'],
                'description'   => $tag['description'],
                'domain'        => $tag['domain'],
            ]));
        }
        
        $cities = City::factory(50)->create();
        $streets = Street::factory(15)->make()->each(function($street) use ($cities) {
            $street->city()->associate($cities->random())->save();
        });

        $num_elementi=10;
        //valori casuali per id_sd
        $id_sd_values = ['ABC123', 'DEF456', 'GHI789', 'JKL012', 'MNO345', 'PQR678', 'STU901', 'VWX234', 'YZA567', 'BCD890'];

        $civic_values = ['232', '35', '132', '243', '53', '11', '32'];

        $time_stamp_pulizia = 1685865812;
        $time_stamp_pulizie = [];
        for ($i=0;$i<$num_elementi;$i++){
            $time_stamp_pulizie[]=$time_stamp_pulizia+15000;
            $time_stamp_pulizia+=15000;
        }

        $user_app = ['user1', 'user2', 'user3', 'user4'];
        $lat_app = ['lat1','lat2', 'lat3', 'lat4'];
        $long_app = ['long1','long2', 'long3', 'long4'];
        
        $items = Item::factory($num_elementi)->make([
            'user_id' => 2,
            ])->each(function($item) use ($streets, $tags, $time_stamp_pulizie, $user_app, $lat_app, $long_app, $id_sd_values, $civic_values, $newtag) {
            $item->street()->associate($streets->random())->save();

            $time_stamp_pulizia = date('Y-m-d H:i:s', $time_stamp_pulizie[array_rand($time_stamp_pulizie)]);
            
            $item->id_da_app = $time_stamp_pulizia . "_" . $user_app[array_rand($user_app)] . "_" . $lat_app[array_rand($lat_app)] . "_" . $long_app[array_rand($long_app)];
            $item->time_stamp_pulizia = $time_stamp_pulizia;

            
            $item->id_sd = $id_sd_values[array_rand($id_sd_values)];
            $item->civic = $civic_values[array_rand($civic_values)];

            $cancellabile = rand(1, 2) == 2 ? now() : null;
            $deletedAt = null;

            if (!is_null($cancellabile)) {
                $deletedAt = $cancellabile->copy()->addDay();
            }

            $item->cancellabile = $cancellabile;
            $item->deleted_at = $deletedAt;

            $item->save();

            $item->tags()->attach(
                $newtag->where('domain', 'caditoie')->pluck('id')->toArray()
            );

        });
    }
}