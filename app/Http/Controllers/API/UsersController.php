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
use Storage;
use Str;

class UsersController extends Controller
{

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function getAllUsers() {
        $this->authorize('isAdmin');
        return User::where('id', '!=', auth('api')->user()->id)->latest()->paginate(20);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all users except the authenticated one
        if(auth('api')->user()->user_role == 'admin') {
            $users = User::where('id', '!=', auth('api')->user()->id)->get();
        } else {
            $users = User::where('id', '!=', auth('api')->user()->id)->where('user_role', '=', 'admin')->get();
        }

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
        $this->authorize('isAdmin');

        // check if this user is already in database
        if(User::where('email', '=', $request->email)->count() > 0) {
            return [
                'status' => 'mailerror',
                'message' => 'Ein Benutzer mit dieser E-Mail-Adresse ist bereits im System registriert.',
            ];
        }

        // Validation
        $this->validate($request, [
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:50',
            'description' => 'required|string',
            'business_registration_form' => 'required|mimes:pdf,doc,docx',
            'id_card' => 'required|mimes:pdf,doc,docx',
            'tax_number' => 'required|string',
        ]);

        if($request->hasFile('business_registration_form') && $request->hasFile('id_card')) {
            // get files
            $business_registration_form = $request->business_registration_form;
            $id_card = $request->id_card;
            $name = Str::slug($request->name);

            $file_extension_business_reg_form = pathinfo($business_registration_form->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_extension_id_card = pathinfo($id_card->getClientOriginalName(), PATHINFO_EXTENSION);

            $filename_business_reg_form =   time() . '.' . $file_extension_business_reg_form;
            $filename_id_card = (time() + 1) . '.' . $file_extension_id_card;

            // save files
            $save_path = 'documents/';

            Storage::disk('public')->put($save_path.$name . '-' .$filename_business_reg_form, File::get($business_registration_form));
            Storage::disk('public')->put($save_path.$name . '-' .$filename_id_card, File::get($id_card));

            
            // generate raw password to send user with email
            $password = Str::random(8);
            $hashed_password = Hash::make($password);
            $newUser = User::create([
                'company_name' => $request->company_name,
                'business_registration_form' => $name . '-' .$filename_business_reg_form,
                'id_card' => $name . '-' .$filename_id_card,
                'tax_number' => $request->tax_number,
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'password' => $hashed_password,
                'company_description' => $request->description,
                'user_role' => 'user',
            ]);

            // is new user is created then remove it from dealer applications
            if($newUser) {
                // send an email with user credentials
                $mailStatus = sendNotificationEmail($newUser, $password, 'templates.mail.user-confirmation-mail', 'Benutzeranmeldeinformationen');
                return [
                    'status' => 'success',
                    'user_message' => 'Benutzer wird erstellt',
                    'mail_message' => $mailStatus,
                ];
            } else {
                return ['message' => 'Fehler beim Hinzufügen eines neuen Benutzers.'];
            }
        } else {
            return response()->json([
                'message' => 'Serverfehler! Fehler beim Hochladen der Dateien!',
            ]);
        }
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

        $user = User::findOrFail($id);
        if($user->delete()){
            // documents that are user related must also be deleted
            // save files
            $save_path = 'documents/';
            Storage::disk('public')->delete([
                $save_path.$user->business_registration_form,
                $save_path.$user->id_card
            ]);

            $mailStatus = sendNotificationEmail($user, null, 'templates.mail.user-delete-mail', 'Ihre Registrierung wurde gelöscht.');

            return [
                'message' => 'Benutzer gelöscht.',
                'mail_message' => $mailStatus
            ];
        } else {
            return ['message' => 'Serverfehler! Der Benutzer konnte nicht gelöscht werden.'];
        }
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

        if(!empty($request->description)){
            $user->company_description = $request->description;
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
            return ['message' => 'Profilinformationen aktualisiert'];
        }else {
            return ['message' => 'Aktualisierungsfehler'];
        }
    }

    public function sendNewUserPassword($id) {

        $this->authorize('isAdmin');

        $user = User::findOrFail($id);
        // Generate new password and send to the user if the new password stored successfully
        $password = Str::random(8);
        $hashed_password = Hash::make($password);
        $user->password = $hashed_password;

        if($user->save()) {
            $mailStatus = sendNotificationEmail($user, $password, 'templates.mail.user-confirmation-mail', 'Benutzeranmeldeinformationen');
            return [
                'status' => 'success',
                'user_message' => 'Neue Anmeldeinformationen gesendet',
                'mail_message' => $mailStatus,
            ];
        } else {
            return ['message' => 'Serverfehler! Neues Passwort konnte nicht erstellt werden.'];
        }
    }
}
