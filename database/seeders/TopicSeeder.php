<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('topics')->insert([
            'topic' => 'topic_1',
        ]);
        DB::table('topics')->insert([
            'topic' => 'topic_2',
        ]);
        DB::table('topics')->insert([
            'topic' => 'topic_3',
        ]);
    }
}
