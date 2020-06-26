<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use DB;

class UsersController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all users except the authenticated one
        $users = User::where('id', '!=', auth('api')->user()->id)->get();

        $unreadIds = Message::select(DB::raw('`from_user` as sender_id, count(`from_user`) as messages_count'))
            ->where('to_user', auth('api')->user()->id)
            ->where('read', false)
            ->groupBy('from_user')
            ->get();
        
        $users = $users->map(function($user) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $user->id)->first();
            // add new key/value pair to users
            $user->unread = $contactUnread ? $contactUnread->messages_count : 0;

            return $user;
        });

        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
