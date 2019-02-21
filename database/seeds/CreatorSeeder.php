<?php

use Illuminate\Database\Seeder;

class CreatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Creator::class, 20)->create(['type' => \App\Models\Creator::TYPE_AUTHOR]);
        factory(\App\Models\Creator::class, 10)->create(['type' => \App\Models\Creator::TYPE_PUBLISHER]);
    }
}
