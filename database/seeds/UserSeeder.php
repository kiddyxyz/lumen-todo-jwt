<?php

use App\User;
use Carbon\Carbon;
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
        $user = new User();
        $user->name = "M Hudya Ramadhana";
        $user->email = "mhudyaramadhana@gmail.com";
        $user->client_id = md5(Carbon::now());
        $user->client_secret = md5(rand(10000,999999));
        $user->save();
    }
}
