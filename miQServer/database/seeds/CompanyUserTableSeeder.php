<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CompanyUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_users')->insert([
            'company_id' => '1',
            'user_id'=> '1',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('company_users')->insert([
            'company_id' => '1',
            'user_id'=> '2',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('company_users')->insert([
            'company_id' => '1',
            'user_id'=> '3',
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);
    }
}
