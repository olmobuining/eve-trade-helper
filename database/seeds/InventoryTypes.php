<?php

use Illuminate\Database\Seeder;

class InventoryTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents(base_path() . "/database/seeds/inv_types.sql"));
        DB::unprepared(file_get_contents(base_path() . "/database/seeds/inv_types2.sql"));
        DB::unprepared(file_get_contents(base_path() . "/database/seeds/inv_types3.sql"));
        DB::unprepared(file_get_contents(base_path() . "/database/seeds/inv_types4.sql"));
    }
}
