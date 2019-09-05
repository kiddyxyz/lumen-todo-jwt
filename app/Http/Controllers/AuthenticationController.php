<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Hamcrest\Thingy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthenticationController extends Controller
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
        $id = $request->client_id;
        $secret = $request->client_secret;

        $user = User::where('client_id', $id)->where('client_secret', $secret)->first();

        if(!$user){
            return $this->unAuthorized('', 'Wrong Credentials!');
        }

        $token = JWT::encode(['user_id' => $user->id], env('JWT_KEY'), env('JWT_ALG'));
        return $this->ok([
            "id" => $user->id,
            "email" => $user->email,
            "name" => $user->name,
            "token" => $token
        ], 'Authenticated!');
    }

    public function create(Request $request){
        $client_id = md5(Carbon::now());
        $client_secret = md5(rand(10000,999999));
        $name =  $request->name;
        $email = $request->email;

        $check = User::where('email', $email)->first();
        if($check){
            $this->sendMail($email, $name, $check->client_id, $check->client_secret);
            return $this->badRequest('', "$email has been registered!");
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->client_id = $client_id;
        $user->client_secret = $client_secret;
        $user->save();

        $this->sendMail($email, $name, $client_id, $client_secret);

        return $this->ok($user, "Created!");
    }

    private function sendMail($email, $name, $client_id, $client_secret){
        try{
            Mail::send('email', ['name' => $name, 'client_id' => $client_id, 'client_secret' => $client_secret],
                function ($message) use ($email, $name)
            {
                $app_name = env('APP_NAME');
                $message->subject("Hi $name, welcome to $app_name API");
                $message->from('donotreply@kiddy.com', 'Kiddy');
                $message->to($email);
            });
        }
        catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }
}
