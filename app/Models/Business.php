<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Business extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'designation',
        'sub_title',
        'description',
        'branding_text',
        'banner',
        'logo',
        'card_theme',
        'theme_color',
        'links',
        'meta_keyword',
        'meta_description',
        'meta_image',
        'domains',
        'enable_businesslink',
        'subdomain',
        'enable_domain',
        'created_by'

    ];
    
    
     protected function cardTheme(): Attribute
    {
        return Attribute::make(
            get: function($value){
                $valuex= json_decode($value);
         
                if(is_object($valuex->order) && count((array)$valuex->order) > 0){
                    // $orders= $valuex->order;
                    // $valuex->order= [];
                    // $plus=false;
                    // foreach($orders as $key => $val){
                        
                    //     $valuex->order[$key]= intval($val) + ($plus ? 1 : 0);
                        
                    //     if($key=="custom_html"){
                    //         $valuex->order["map_iframe"]= intval($val) + 1;
                    //         $plus= true;
                    //     }
                        
                        
                    // }
                    
                //   $value=$valuex;
                    if(!property_exists( $valuex->order, "map_iframe")){
                         $valuex->order->map_iframe= count( (array) $valuex->order ) + 1;
                    }
                   
                }
                
                return json_encode($valuex);
            },
        );
    }

    public function getLanguage(){
        if (\Auth::user()->type == 'company')
        {
            
            $user = User::find($this->created_by);
        }
        else{
            
            $user = User::where('created_by','=',$this->created_by)->first();
            
        }
        return $user->currentLanguage();
       
    }

    public static function pwa_business($slug){

        $business = Business::where('slug', $slug)->first();
        try {
            
            $pwa_data = \File::get(storage_path('uploads/theme_app/business_' . $business->id. '/manifest.json'));

            $pwa_data = json_decode($pwa_data);
        } catch (\Throwable $th) {
            $pwa_data = [];
        }
        return $pwa_data;

    }

    public static function allBusiness()
    {
        $businesses = [];
        if (\Auth::user()->type == 'company') {
            $businesses = Business::where('created_by', \Auth::user()->id)->pluck('title', 'id')->toArray();
        } else {
            $businesses = Business::where('created_by', \Auth::user()->creatorId())->pluck('title', 'id')->toArray();
        }
        return $businesses;
    }

    // Add relationships for eager loading
    public function businessHours()
    {
        return $this->hasOne(business_hours::class, 'business_id');
    }

    public function appointments()
    {
        return $this->hasOne(appoinment::class, 'business_id');
    }

    public function contactInfo()
    {
        return $this->hasOne(ContactInfo::class, 'business_id');
    }

    public function services()
    {
        return $this->hasOne(service::class, 'business_id');
    }

    public function testimonials()
    {
        return $this->hasOne(testimonial::class, 'business_id');
    }

    public function socialLinks()
    {
        return $this->hasOne(social::class, 'business_id');
    }

    public function gallery()
    {
        return $this->hasOne(Gallery::class, 'business_id');
    }

    public function products()
    {
        return $this->hasOne(Product::class, 'business_id');
    }

    public function businessQr()
    {
        return $this->hasOne(Businessqr::class, 'business_id');
    }

    public function pixelFields()
    {
        return $this->hasMany(PixelFields::class, 'business_id');
    }

    public static function card_cookie($slug)
    {
        $data = Business::where('slug', '=', $slug)->first();
        return $data->gdpr_text;
    }

    public static $qr_type = [
        0 => 'Normal',
        2 => 'Text',
        4 =>'Image',
    ];
    
}
