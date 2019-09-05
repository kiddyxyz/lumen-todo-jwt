<?php

namespace App\Http\Controllers;

use App\Todo;
use App\User;
use Illuminate\Http\Request;

class TodoController extends Controller
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

    public function index(Request $request)
    {
        $finished = $request->finished;
        $token = $this->decodeToken($request);

        if($finished && $finished == "1"){
            $todo = Todo::onlyTrashed()->where('user_id', $token->user_id)->paginate(10);
        }else{
            $todo = Todo::withoutTrashed()->where('user_id', $token->user_id)->paginate(10);
        }

        return $this->ok($todo, '');
    }

    public function show(Request $request)
    {
        $token = $this->decodeToken($request);

        $todo = Todo::withTrashed()->where('id', $request->id)
            ->where('user_id', $token->user_id)
            ->first();

        if(!$todo){
            return $this->notFound('', "Todo Not Found!");
        }

        return $this->ok($todo, '');
    }

    public function store(Request $request)
    {
        $token = $this->decodeToken($request);

        $title = $request->title;
        $notes = $request->notes;

        if(!$title  || !$notes){
            return $this->badRequest('', "title and notes should be written in body!");
        }

        $todo = new Todo();
        $todo->title = $title;
        $todo->notes = $notes;
        $todo->user_id = $token->user_id;
        $todo->save();

        return $this->ok($todo, 'Successfully create Todo!');
    }

    public function update(Request $request, $id)
    {
        $token = $this->decodeToken($request);

        $title = $request->title;
        $notes = $request->notes;

        if(!$title  || !$notes){
            return $this->badRequest('', "title and notes should be written in body!");
        }

        $todo = Todo::withTrashed()->where('id', $id)
            ->where('user_id', $token->user_id)
            ->first();

        if(!$todo){
            return $this->notFound('', "Todo Not Found!");
        }

        $todo->title = $title;
        $todo->notes = $notes;
        $todo->user_id = $token->user_id;
        $todo->save();

        return $this->ok($todo, 'Successfully update Todo!');
    }

    public function finish(Request $request, $id)
    {
        $token = $this->decodeToken($request);

        if(!$id){
            return $this->badRequest('', "title and notes should be written in body!");
        }

        $todo = Todo::where('id', $id)
            ->where('user_id', $token->user_id)
            ->first();

        if(!$todo){
            return $this->notFound('', "Todo Not Found!");
        }

        $todo->delete();

        return $this->ok($todo, 'Successfully finish Todo!');
    }

    public function delete(Request $request, $id)
    {
        $token = $this->decodeToken($request);

        $todo = Todo::onlyTrashed()->where('id', $id)
            ->where('user_id', $token->user_id)
            ->first();

        if(!$todo){
            return $this->notFound('', "Todo Not Found!");
        }

        $todo->forceDelete();

        return $this->ok($todo, 'Successfully delete Todo!');
    }
}
