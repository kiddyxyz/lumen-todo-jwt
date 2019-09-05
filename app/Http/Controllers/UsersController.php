<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function store(Request $request){
        $email = $request->email;
        $name = $request->name;

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->save();

        return $this->ok($user, "Registered!");
    }
}
