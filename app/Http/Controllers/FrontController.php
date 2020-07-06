<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Dealer;
use File;
use Str;
use Storage;

class FrontController extends Controller
{
    public function index() {
        return view('welcome');
    }

    public function showApplyForm() {
        return view('apply-form');
    }

    public function applyAsDealer(Request $request) {
        // Validation
        $this->validate($request, [
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:dealers,email',
            'phone' => 'required|string|max:50',
            'description' => 'required|string|min:40',
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

            
            // Create the dealer
            $dealer =  Dealer::create([
                'company_name' => $request->company_name,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'business_registration_form' => $name . '-' .$filename_business_reg_form,
                'id_card' => $name . '-' .$filename_id_card,
                'tax_number' => $request->tax_number
            ]);

            // send dealer candidate an email that we got his application
            $mailStatus = sendNotificationEmail($dealer, null, 'templates.mail.dealer-form-notification-mail', 'Application received');

            return response()->json([
                'message' => 'Message successfully sent',
                'mail_message' => $mailStatus,
            ]);
        } else {
            return response()->json([
                'message' => 'Server error! Try again!',
            ]);
        }
    }
}
