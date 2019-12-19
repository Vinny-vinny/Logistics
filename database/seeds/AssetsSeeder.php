<?php

use Illuminate\Database\Seeder;
use App\Machine;
use App\User;
class AssetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Machine::create([
          'code' => 'KAZ417L',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'LANDCRUI',
          'asset_category_id' => 1,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
        Machine::create([
          'code' => 'KCB845F',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'LANDCRUI',
          'asset_category_id' => 1,
          'track_by_id' => 1,
          'chasis_no' => '',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
        Machine::create([
          'code' => 'KCP017P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 1,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
        Machine::create([
          'code' => 'KMEN90K',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 1,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
        Machine::create([
          'code' => 'KAQ433P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
        Machine::create([
          'code' => 'KAP065N',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
         Machine::create([
          'code' => 'KAZ914L',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
          Machine::create([
          'code' => 'KAT819U',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'KBX649P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
            Machine::create([
          'code' => 'KAP068N',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
             Machine::create([
          'code' => 'KBB511T',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
              Machine::create([
          'code' => 'KAQ045U',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                  Machine::create([
          'code' => 'KBW729R',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                      Machine::create([
          'code' => 'KAZ225L',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                          Machine::create([
          'code' => 'KMDQ802D',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'KMCA863R',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
            Machine::create([
          'code' => 'KBJ479J',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
             Machine::create([
          'code' => 'KAZ098V',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
              Machine::create([
          'code' => 'KTCB006N',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
               Machine::create([
          'code' => 'KTCB777U',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                Machine::create([
          'code' => 'KBJ480J',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                 Machine::create([
          'code' => '12.5KVA SOCIAL HALL',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                  Machine::create([
          'code' => 'PERKINS 100 KVA HQ',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'PERKINS 16.5KVA',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                                  Machine::create([
          'code' => 'HONDA PORTABLE',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
                                          Machine::create([
          'code' => '45 KVA C.E.O.HSE',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'D36',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'HONDA PORTABLE 5KVA',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 2,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KCT232W',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KBS639T',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'PERKINS 20KVA TM',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KMDU733W',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KMDU731W',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KMDU732W',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KMDD022S',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 3,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);

           Machine::create([
          'code' => 'KBX013Z',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                  Machine::create([
          'code' => 'KBM324V',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                         Machine::create([
          'code' => 'KCB846F',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                                Machine::create([
          'code' => 'KCT544W',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                                       Machine::create([
          'code' => 'KMDF790P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                                              Machine::create([
          'code' => 'KMDF788P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                                                     Machine::create([
          'code' => 'KMCF822E',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
          Machine::create([
          'code' => 'KMCG948P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 4,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'KBN287V',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 5,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'KCL150P',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 5,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
                Machine::create([
          'code' => 'KCU116C',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 5,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
           Machine::create([
          'code' => 'KAT842C',
          'description' => 'PICKUP 4X4',
          'make' => 'TOYOTA',
          'model' => 'TOYOTA',
          'asset_category_id' => 6,
          'track_by_id' => 1,
          'chasis_no' => 'HZJ79R 7067988',
          'type' => 'Internal',
          'body_type' => 'FLAT DECK',
          'color' => 'GREEN',
          'log_book' => 'R0098116Y',
          'cost_center' => 'R & M Vehicles - Security',
          'fleet' => 'GN30',
          'assign_to' => User::all()->random()->id
        ]);
    }
}
