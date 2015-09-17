<?php

use Illuminate\Database\Seeder;

class create_categories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
        	'title' => 'Who is God?'
        ]);

        DB::table('categories')->insert([
        	'title' => 'What has God done?'
        ]);

        DB::table('categories')->insert([
        	'title' => 'Who are we?'
        ]);

        DB::table('categories')->insert([
        	'title' => 'What do we do?'
        ]);
    }
}
