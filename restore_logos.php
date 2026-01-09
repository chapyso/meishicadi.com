<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$settings = [
    ["name"=>"company_favicon","value"=>"5_favicon.png","created_by"=>5],
    ["name"=>"company_favicon","value"=>"6_favicon.png","created_by"=>6],
    ["name"=>"company_favicon","value"=>"13_favicon.png","created_by"=>13],
    ["name"=>"company_favicon","value"=>"19_favicon.png","created_by"=>19],
    ["name"=>"company_logo","value"=>"company_logo1693946123.png","created_by"=>3],
    ["name"=>"company_logo","value"=>"company_logo1698093577.png","created_by"=>5],
    ["name"=>"company_logo","value"=>"company_logo1694168723.png","created_by"=>6],
    ["name"=>"company_logo","value"=>"company_logo1694335430.png","created_by"=>13],
    ["name"=>"company_logo","value"=>"company_logo1694683329.png","created_by"=>15],
    ["name"=>"company_logo","value"=>"company_logo1694874182.png","created_by"=>19],
    ["name"=>"company_logo","value"=>"company_logo1696243201.png","created_by"=>42],
    ["name"=>"company_logo","value"=>"company_logo1696249629.png","created_by"=>53],
    ["name"=>"company_logo","value"=>"company_logo1698233819.png","created_by"=>58],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1693946123.png","created_by"=>3],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1696249629.png","created_by"=>5],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1694168723.png","created_by"=>6],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1694335430.png","created_by"=>13],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1694683329.png","created_by"=>15],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1694874182.png","created_by"=>19],
    ["name"=>"company_logo_light","value"=>"company_logo_light_1698233819.png","created_by"=>58]
];

foreach ($settings as $setting) {
    echo "Restoring {$setting['name']} for user {$setting['created_by']} to {$setting['value']}... ";
    
    // Check if the record exists first to decide on update or assume existing
    // The key constraint is usually (created_by, name). 
    // We update the 'value'.
    
    $updated = DB::table('settings')
        ->where('created_by', $setting['created_by'])
        ->where('name', $setting['name'])
        ->update(['value' => $setting['value']]);
        
    if ($updated) {
        echo "UPDATED\n";
    } else {
        // If not updated, it might be that the value was already correct, OR the row doesn't exist.
        // Let's count if row exists.
        $exists = DB::table('settings')
            ->where('created_by', $setting['created_by'])
            ->where('name', $setting['name'])
            ->exists();
            
        if ($exists) {
            echo "SKIPPED (Match)\n";
        } else {
            echo "INSERTING... ";
            DB::table('settings')->insert([
                'created_by' => $setting['created_by'],
                'name' => $setting['name'],
                'value' => $setting['value']
            ]);
            echo "DONE\n";
        }
    }
}
echo "Logo restoration complete.\n";
