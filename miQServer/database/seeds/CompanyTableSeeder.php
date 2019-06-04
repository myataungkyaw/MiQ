<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Your Company Name',
            'address'=> 'your company address',
            'background_image'=> '',
            'logo'=>'',
            'log_retention_period' => 30,
            'queue_prefix' => 'A',
            'third_party_integration'=> 0,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);
    }
}
