<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('en_GB');
        $faker->addProvider(new Faker\Provider\en_GB\Address($faker));

        for ($i=1; $i<= 10; $i++) {
            DB::table('items')->insert([
                'name' => $faker->name,
                'description' => $faker->paragraph(1),
                'image' => "https://previews.123rf.com/images/aquir/aquir1311/aquir131100316/23569861-sample-grunge-red-round-stamp.jpg",
                'price' => $faker->numberBetween(0, 1000)
            ]);
        }
    }
}
