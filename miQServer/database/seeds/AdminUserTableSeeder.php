<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'phone'=> '09975452184',
            'email' => 'superadmin.miq@gmail.com',
            'password' => bcrypt('p@$$w0rd@123456'),
            'role'=> 1,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'phone'=> '09790552779',
            'email' => 'admin.miq@gmail.com',
            'password' => bcrypt('p@$$w0rd@123456'),
            'role'=> 2,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);

        DB::table('users')->insert([
            'name' => 'Staff',
            'phone'=> '09975088611',
            'email' => 'staff.miq@gmail.com',
            'password' => bcrypt('p@$$w0rd@123456'),
            'role'=> 3,
            'created_at'=> Carbon::now(),
            'updated_at'=> Carbon::now()
        ]);


    }
}
