<?php

use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        UserFactory::new()->count(10)->create();
    }
}
