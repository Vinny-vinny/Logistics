<?php

use Illuminate\Database\Seeder;

class MechanicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Mechanic::create([
           'name' => 'John Doe',
           'email' => 'johndoe@gmail.com',
           'phone' => '0700000001',
           'address' => 'Box 19 Malakisi',
        ]);
        \App\Mechanic::create([
            'name' => 'Joshua Kibor',
            'email' => 'jkibor@gmail.com',
            'phone' => '0700000021',
            'address' => 'Box 199 Kolanya',
        ]);
    }
}
