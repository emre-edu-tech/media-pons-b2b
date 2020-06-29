<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dealer;
use Illuminate\Support\Facades\Hash;
use Str;
use App\User;

class DealersController extends Controller
{
    // added for api security
    // allows only javascript applications with JWT to access api route
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
        $this->authorize('isAdmin');
        return Dealer::latest()->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // add dealer by admin
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
        $this->authorize('isAdmin');

        $dealer = Dealer::findOrFail($id);
        $dealer->delete();

        return ['message' => 'Başvuru silindi'];
    }


    // custom functions
    public function acceptDealer(Request $request) {
        $this->authorize('isAdmin');
        
        // check if this dealer was accepted before
        if(User::where('email', '=', $request->email)->count() > 0) {
            return [
                'status' => 'mailerror',
                'message' => 'Bu e-posta adresine sahip bir kullanıcı sistemde zaten kayıtlı',
            ];
        }
        // this function gets dealer information and adds it as a user
        // pay attention to user role
        // generate raw password to send user with email
        $password = Str::random(8);
        $hashed_password = Hash::make($password);
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $hashed_password,
            'user_role' => 'user',
        ]);
        // is new user is created then remove it from dealer applications
        if($newUser) {
            $this->destroy($request->id);
            return [
                'status' => 'success',
                'user_message' => 'User is created',
                'dealer_message' => 'Application is deleted',
            ];
        } else {
            return ['message' => 'Error while processing'];
        }
    }
}