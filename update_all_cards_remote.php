<?php


// The CORRECT content from the verified local version
// Contains the `img_1768084479...` images.
$masterContent = '[{"id":1,"title":"TRAFFIC SERVICES","description":"From complete ramp handling and VIP Fixed Base Operations (FBO) to baggage management and equipment support, our solutions are designed to keep your operations running smoothly. Our robust Operations Control Center (OCC) ensures 24\/7 readiness, backed by a dedicated team conducting regular audits and inspections to meet the highest international standards.","purchase_link":"https:\/\/bas.com.bh\/services\/ground-operations-and-terminal-services","link_title":"LEARN MORE","image":"img_1768084479273901501.jpg"},{"id":2,"title":"TERMINAL SERVICES","description":"From the moment passengers arrive, BAS is committed to creating an exceptional travel experience. Our comprehensive terminal services cover everything from airline representation and efficient baggage handling to specialized support for additional needs.","purchase_link":"https:\/\/bas.com.bh\/services\/ground-operations-and-terminal-services","link_title":"LEARN MORE","image":"img_1768084479919662013.jpg"},{"id":3,"title":"CARGO SERVICES","description":"Efficient, secure, and seamless\u2014BAS Cargo Services offers a full spectrum of freight solutions designed to meet your airline\u2019s unique needs. From perishables to high-value cargo, our state-of-the-art 19,000 sqm terminal is equipped to handle it all.","purchase_link":"https:\/\/bas.com.bh\/services\/cargo-services","link_title":"LEARN MORE","image":"img_17680844791665627385.jpg"},{"id":4,"title":"CATERING SERVICES","description":"BAS Catering sets the benchmark for aviation and non-aviation culinary services. From preparing 8.6 million meals annually to catering for luxury lounges like Pearl Lounge and Gulf Air\u2019s Falcon Lounge, our HACCP- and ISO-certified facilities ensure premium quality every time.","purchase_link":"https:\/\/bas.com.bh\/services\/catering-services","link_title":"LEARN MORE","image":"img_17680844791348895274.jpg"},{"id":5,"title":"AIRCRAFT ENGINEERING SERVICES","description":"BAS\u2019s Aircraft Engineering Services provides world-class line maintenance, offering scheduled and on-call support across a broad range of aircraft, including the latest Airbus and Boeing models.","purchase_link":"https:\/\/bas.com.bh\/services\/aircraft-engineering-services","link_title":"LEARN MORE","image":"img_1768084479612670744.jpg"},{"id":6,"title":"BAS AIRCRAFT ENGINEERING TRAINING CENTER (BAETC)","description":"At the BAS Aircraft Engineering Training Center (BAETC), we\'re setting global standards in aviation maintenance training, recognized for our advanced technological capabilities and commitment to quality. Ranked second globally for EASA accreditation, our programs are tailored to foster the next generation of aviation professionals, meeting both EASA and UKCAA standards.","purchase_link":"https:\/\/baetc.org\/","link_title":"LEARN MORE","image":"img_17680844791326855281.jpg"}]';

$ownerId = 121;
$businesses = \App\Models\Business::where('created_by', $ownerId)->get();

echo "Updating " . $businesses->count() . " businesses on REMOTE server...\n";

foreach ($businesses as $business) {
    $subService = \App\Models\service::where('business_id', $business->id)->first();
    
    if ($subService) {
        $subService->content = $masterContent;
        $subService->save();
        echo "Updated services for business {$business->id}\n";
    } else {
        $newService = new \App\Models\service();
        $newService->business_id = $business->id;
        $newService->content = $masterContent;
        $newService->is_enabled = 1;
        $newService->created_by = $ownerId;
        $newService->save();
        echo "Created services for business {$business->id}\n";
    }
}

echo "Done. All remote cards should now show the correct images.\n";
