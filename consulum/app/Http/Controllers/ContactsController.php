<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use App\Models\Business;
use App\Models\User;
use App\Models\Utility;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Appointment_deatail;
use Illuminate\Support\Facades\Validator;
class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id="")
    {
        $user=\Auth::user();
        $business_id=$user->current_business;
        if(\Auth::user()->can('manage contact'))
        {
            // Super admin sees all contacts from all businesses (leads page)
            if($user->type == 'super admin'){
                $contacts_deatails = Contacts::all();
            } elseif($business_id=="" || $business_id=="0"){
                $contacts_deatails = Contacts::where('created_by',\Auth::user()->creatorId())->get();
            } else {
                $contacts_deatails = Contacts::where('created_by',\Auth::user()->creatorId())->where('business_id',$business_id)->get();
            }
            
            // Add business name and country information to each contact
            foreach ($contacts_deatails as $key => $value) {
                $business_name = Business::where('id',$value->business_id)->pluck('title')->first();
                $value->business_name = $business_name;
                // Add country information based on phone number
                $countryInfo = get_country_from_phone($value->phone);
                $value->country = $countryInfo['country'];
                $value->country_code = $countryInfo['code'];
                $value->phone_country_code = $countryInfo['country_code'];
            }
            
            // Handle sorting by country
            $sortBy = \Request::get('sort_by', 'none');
            if($sortBy == 'country') {
                $contacts_deatails = $contacts_deatails->sortBy(function($contact) {
                    return $contact->country;
                })->values();
            }
            
            return view('contacts.index',compact('contacts_deatails'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
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
        $validator = Validator::make(
            $request->all(),
            [
                'business_id' => 'required|exists:businesses,id',
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'phone' => 'required|string|max:50',
                'message' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            $msg = $validator->errors()->first();
            if ($request->ajax()) {
                return $this->contactResponse(false, $msg, 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        $business = Business::find($request->business_id);

        $contact = Contacts::create([
            'business_id' => $business->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'status' => 'pending',
            'created_by' => $business->created_by,
        ]);

        // Get business email - improved extraction logic
        $recipientEmail = $this->getBusinessEmail($business);

        // Always send email if we have a recipient
        if (!empty($recipientEmail) && filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            try {
                $data = [
                    "name" => $request->name,
                    "email" => $request->email,
                    'phone' => $request->phone,
                    'message' => $request->message,
                    'business_name' => $business->title ?? '',
                ];

                Mail::to($recipientEmail)->send(new ContactMail($data));
            } catch (\Exception $e) {
                // Log error but don't fail the request
                \Log::error('Failed to send contact email: ' . $e->getMessage());
            }
        }

        $successMessage = __('Contact Created Successfully.');

        // Support both AJAX and normal requests
        if ($request->ajax()) {
            return $this->contactResponse(true, $successMessage);
        }

        return redirect()->back()->with('success', $successMessage);
    }

    /**
     * Extract business email from ContactInfo or fallback to owner email
     *
     * @param Business $business
     * @return string
     */
    protected function getBusinessEmail($business)
    {
        $recipientEmail = '';

        // Try to get email from ContactInfo
        $contactInfo = \App\Models\ContactInfo::where('business_id', $business->id)->first();
        
        if ($contactInfo && $contactInfo->content) {
            $decoded = json_decode($contactInfo->content, true);
            
            if ($decoded) {
                // Recursively search for email in the JSON structure
                $recipientEmail = $this->findEmailInArray($decoded);
            }
        }

        // Fallback to owner email if business email not found
        if (empty($recipientEmail) || !filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
            $owner = User::find($business->created_by);
            if ($owner && !empty($owner->email) && filter_var($owner->email, FILTER_VALIDATE_EMAIL)) {
                $recipientEmail = $owner->email;
            }
        }

        return $recipientEmail;
    }

    /**
     * Recursively search for email in array/object structure
     *
     * @param mixed $data
     * @return string
     */
    protected function findEmailInArray($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                // Check if key is email-related
                $keyLower = strtolower($key);
                if (in_array($keyLower, ['email', 'contact_email', 'business_email', 'e-mail', 'mail'])) {
                    if (is_string($value) && !empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $value;
                    }
                }
                
                // Recursively search in nested arrays/objects
                if (is_array($value) || is_object($value)) {
                    $found = $this->findEmailInArray($value);
                    if (!empty($found)) {
                        return $found;
                    }
                }
            }
        } elseif (is_object($data)) {
            foreach ($data as $key => $value) {
                $keyLower = strtolower($key);
                if (in_array($keyLower, ['email', 'contact_email', 'business_email', 'e-mail', 'mail'])) {
                    if (is_string($value) && !empty($value) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        return $value;
                    }
                }
                
                if (is_array($value) || is_object($value)) {
                    $found = $this->findEmailInArray($value);
                    if (!empty($found)) {
                        return $found;
                    }
                }
            }
        }

        return '';
    }

    /**
     * Standardize contact responses for AJAX/non-AJAX.
     *
     * @param bool $flag
     * @param string $msg
     * @param int $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function contactResponse(bool $flag, string $msg, int $status = 200)
    {
        return response()->json(
            [
                'flag' => $flag,
                'msg' => $msg,
            ],
            $status
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment_deatail  $appointment_deatail
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment_deatail $appointment_deatail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Appointment_deatail  $appointment_deatail
     * @return \Illuminate\Http\Response
     */
    public function edit(Contacts $Contacts,$id)
    {
        $Contacts = Contacts::where('id',$id)->first();
        return view('contacts.edit',compact('Contacts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment_deatail  $appointment_deatail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contacts $Contacts,$id)
    {
        $validator = \Validator::make(
            $request->all(), [
                                'name' => 'required',
                                'email' => 'required',
                                'phone' => 'required|numeric',
                                'message' => 'required',
                            ]
        );
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $Contacts = Contacts::where('id',$id)->first();
        $Contacts->name     = $request->name;
        $Contacts->email = $request->email;
        $Contacts->phone    = $request->phone;
        $Contacts->message  = $request->message;
        $Contacts->save();

        return redirect()->route('contacts.index')->with('success', __('Contact successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment_deatail  $appointment_deatail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contacts $Contacts,$id)
    {
        if(\Auth::user()->can('delete contact'))
        {
            $contact = Contacts::find($id);
            if($contact){
                $contact->delete();
                return redirect()->back()->with('success', __('Contact successfully deleted.'));
            }
            return redirect()->back()->with('error', __('Contact not found.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    public function getCalenderAllData($id=null){

        $objUser          = \Auth::user();
        if($id== null){
            $appointents = Appointment_deatail::get();
        }else{
            $appointents = Appointment_deatail::where('business_id',$id)->get();
        }
        $arrayJson = [];
        foreach($appointents as $appointent)
        {
            $time = explode('-',$appointent->time);
            $stime = isset($time[0])?trim($time[0]).':00':'00:00:00';
            $etime = isset($time[1])?trim($time[1]).':00':'00:00:00';
            $start_date = date("Y-m-d",strtotime($appointent->date)).' '.$stime;
            $end_date = date("Y-m-d",strtotime($appointent->date)).' '.$etime;

            $arrayJson[] = [
                "title" =>'('.$stime .' - '. $etime.') '.$appointent->name .'-'. $appointent->getBussinessName(),
                "start" => $start_date,
                "end" => $end_date ,
                "app_id" => $appointent->id,
                "url" => route('appointment.details',$appointent->id),
                "className" =>  'bg-info',
                "allDay" => true,
            ];
        }
        return view('appointments.calender',compact('arrayJson'));

    }
    public function getAppointmentDetails($id){
        $ad = Appointment_deatail::find($id);
        return view('appointments.calender-modal',compact('ad'));
    }
    public function add_note($id){
        
        $contact= Contacts::where('id',$id)->first();
        return view('contacts.add_note',compact('contact'));
    }
    public function note_store($id,Request $request){
        
        if(\Auth::user()->can('edit contact'))
        {
            $contacts = Contacts::where('id',$id)->first();
            $contacts->status = $request->status;
            $contacts->note = $request->note;
            $contacts->save();

            return redirect()->back()->with('Success', __('Note added successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}

