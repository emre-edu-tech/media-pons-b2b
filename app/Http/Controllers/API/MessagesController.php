<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Message;
use App\Events\NewMessage;

class MessagesController extends Controller
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
        $message = Message::create([
            'from_user' => auth('api')->user()->id,
            'to_user' => $request->contact_id,
            'message' => $request->text
        ]);

        broadcast(new NewMessage($message));

        return $message;
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

    public function getMessagesFor($contact_id) {
        // return Message::where('from_user', $contact_id)->orWhere('to_user', $contact_id)->get();

        // mark all messages with the selected contact as read
        Message::where('from_user', $contact_id)->where('to_user', auth('api')->user()->id)->update(['read' => true]);

        $messages = Message::where(function($query) use ($contact_id) {
            $query->where('from_user', auth('api')->user()->id);
            $query->where('to_user', $contact_id);
        })->orWhere(function($query) use ($contact_id) {
            $query->where('from_user', $contact_id);
            $query->where('to_user', auth('api')->user()->id);
        })->get();  // (a = 1 AND b = 2) OR (a = 2 AND b = 1)

        return $messages;
    }
}
