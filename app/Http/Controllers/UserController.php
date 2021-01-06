<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use Auth;
use LRedis;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id=Auth::user()->id;
        $users=User::where('id','!=',$user_id)->get();
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input=$request->all();
        $sender_id=Auth::user()->id;
        Chat::create([
            'sender_id' => $sender_id,
            'receiver_id' => $input['receiver_id'],
            'message' => $input['message']
        ]);
        $redis = LRedis::connection();
		$data = ['message' => $input['message'], 'sender_id' => $sender_id, 'receiver_id' => $input['receiver_id']];
		$redis->publish('message', json_encode($data));
         $arr = array("status" => 200, "msg" => "Chat save successfully.", "data" => []);
         return response($arr);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user=User::find($id);
        $chat=auth()
            ->user()
            ->messages()
            ->where(function ($query) use($id) {
                $query->bySender($id)
                    ->byReceiver(auth()->user()->id);
            })
            ->orWhere(function ($query) use($id) {
                $query->bySender(auth()->user()->id)
                    ->byReceiver($id);
            })
            ->orderBy('id','asc')
            ->get();
           return view('users.chat',compact('chat','user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
