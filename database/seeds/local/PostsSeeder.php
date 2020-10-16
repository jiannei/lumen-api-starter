<?php

use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;

class PostsSeeder extends Seeder
{
    public function run()
    {
        PostFactory::new()->count(1000)->create();
    }
}
