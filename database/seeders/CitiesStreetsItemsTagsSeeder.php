<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Item;
use App\Models\Street;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesStreetsItemsTagsSeeder extends Seeder
{
    public function run(): void
    {
        $domains = ['item'];
        $tags = Tag::factory(10)->sequence(fn($s) => ['name' => 'Tag_'.$s->index, 'type'=>($s->index% 2 != 0)?'type_odd':'type_even', 'domain'=>$domains[array_rand($domains)]])->create();
        
        $cities = City::factory(50)->create();
        $streets = Street::factory(1500)->make()->each(function($street) use ($cities) {
            $street->city()->associate($cities->random())->save();
        });
        $items = Item::factory(50000)->make([
            'user_id' => 2
            ])->each(function($item) use ($streets, $tags) {
            $item->street()->associate($streets->random())->save();

            $item->tags()->attach(
                $tags->where('domain', 'item')->random(rand(1, 1))->pluck('id')->toArray()
            );
        });
    }
}
