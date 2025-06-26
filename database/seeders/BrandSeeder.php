<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = array(
            array('name' => 'Apple', 'slug' => 'apple', 'status' => '1'),
            array('name' => 'Dell', 'slug' => 'dell', 'status' => '1'),
            array('name' => 'HP', 'slug' => 'hp', 'status' => '1'),
            array('name' => 'Vivo', 'slug' => 'vivo', 'status' => '1')
        );

        DB::table('countries')->insert($brands);
    }
}
