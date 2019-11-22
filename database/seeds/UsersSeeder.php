<?php
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        \App\User::create([
            'name' => 'Admin',
            'email' => 'admin@technical.com',
            'code_no' => $faker->numberBetween(1,1000),
            'title' => 'Technical Admin',
            'join_date' => now(),
            'email_verified_at' => now(),
            'password' => bcrypt('Qwerty123!'), // password
            'remember_token' => Str::random(10),
        ]);
        \App\User::create([
            'name' => 'VinnyVinny',
            'email' => 'kituyiv@gmail.com',
            'code_no' => $faker->numberBetween(1,1000),
            'title' => 'Super Admin',
            'join_date' => now(),
            'email_verified_at' => now(),
            'password' => bcrypt('Qwerty123!'), // password
            'remember_token' => Str::random(10),
        ]);
    }
}
