<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use DB;
use Image;
use File;
use Illuminate\Support\Facades\Hash;

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

    // methods about profile info
    public function profile() {
        return auth('api')->user();
    }

    // Update user profile on profile page
    public function updateProfile(Request $request) {
        // get authenticated user
        $user = auth('api')->user();

        // Validate info
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
        ]);

        // Do the simple assignments before everything
        $user->name = $request->name;
        $user->email = $request->email;

        // Update only if bio or phone number informatio supplied
        if(!empty($request->bio)){
            $user->bio = $request->bio;
        }
        
        if(!empty($request->phone_number)){
            $user->phone_number = $request->phone_number;
        }

        // Validate only if password has been entered
        if(!empty($request->password)){
            $this->validate($request, [
                'password' => 'sometimes|required|min:8',
            ]);
            $user->password = Hash::make($request->password);
        }

        // process photo info
        if($request->photo) {
            // get the current photo filename for comparing to delete it after successful upload
            $currentFileName = $user->photo;

            // get the file extension from base64 coded image data
            // gets something like data:image/png
            $fileTypeInfo = substr($request->photo, 0, strpos($request->photo, ';'));
            $extension = explode('/', $fileTypeInfo)[1];
            $filename = time().'.'.$extension;

            $save_path = public_path('img/profile/');

            // check save path exists
            if(!file_exists($save_path)){
                File::makeDirectory($save_path, 0755, true, true);
            }
            $img = Image::make($request->photo);
            $img->resize(150, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->save($save_path.$filename);

            // delete the current photo and assign the new file name
            if(file_exists($save_path.$currentFileName)) {
                unlink($save_path.$currentFileName);
            }
            $user->photo = $filename;
        }

        if($user->save()) {
            return ['message' => 'Profil bilgisi güncellendi'];
        }else {
            return ['message' => 'Güncelleme hatası'];
        }
    }
}
