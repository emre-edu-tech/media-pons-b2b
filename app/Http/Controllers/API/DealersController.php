<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dealer;
use File;
use Str;
use Storage;

class DealersController extends Controller
{
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
        // Validation
        $this->validate($request, [
            'company_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:dealers,email',
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

            
            // Create the dealer
            return Dealer::create([
                'company_name' => $request->company_name,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'business_registration_form' => $filename_business_reg_form,
                'id_card' => $filename_id_card,
                'tax_number' => $request->tax_number
            ]);
        } else {
            return ['message' => 'Server error! Try again!'];
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
        //
    }
}
