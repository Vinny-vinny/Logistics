<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
class AgentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
       $agents = \App\Agent::select('cAgentName','cFirstName','cLastName','cDisplayName','cEmail')->get(['cEmail']);

       foreach ($agents as $agent){
           \App\User::create([
              'name' => $agent->cAgentName,
               'email' => $faker->email,
               'title' => $agent->cDisplayName,
               'join_date'=> \Carbon\Carbon::now(),
               'code_no' => 700,
               'password' => bcrypt('password')
           ]);
       }

    }
}
