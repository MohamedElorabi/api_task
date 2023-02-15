<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'username' =>  'ahmed',
            'mobile_number' => '+20102662626',
            'password' => bcrypt('12356')
        ]);

        $user = User::create([
            'username' =>  'mohames',
            'mobile_number' => '+2011518662626',
            'password' => bcrypt('1236558')
        ]);

        $user = User::create([
            'username' =>  'khaled',
            'mobile_number' => '+2010266255626',
            'password' => bcrypt('5333698')
        ]);

        $user = User::create([
            'username' =>  'yasser',
            'mobile_number' => '+2010692556',
            'password' => bcrypt('1552356')
        ]);

        $user = User::create([
            'username' =>  'mahmoud',
            'mobile_number' => '+0105982363',
            'password' => bcrypt('5955665')
        ]);
    }
}
