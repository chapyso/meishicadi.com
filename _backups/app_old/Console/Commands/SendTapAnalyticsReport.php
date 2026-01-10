<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TapAnalytics;
use App\Models\User;
use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTapAnalyticsReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tap-analytics:send-report {--type=weekly : Report type (weekly/monthly)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tap analytics report to admins';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->option('type');
        $this->info("Generating {$type} tap analytics report...");

        $reportData = $this->generateReportData($type);
        
        // Send to super admins
        $superAdmins = User::where('type', 'super admin')->get();
        
        foreach ($superAdmins as $admin) {
            $this->sendReportEmail($admin, $reportData, $type);
        }

        $this->info("Report sent to " . $superAdmins->count() . " admin(s)");
        
        return 0;
    }

    /**
     * Generate report data
     */
    private function generateReportData($type)
    {
        $startDate = $type === 'monthly' 
            ? Carbon::now()->subMonth() 
            : Carbon::now()->subWeek();

        $endDate = Carbon::now();

        $data = [
            'period' => [
                'start' => $startDate->format('M d, Y'),
                'end' => $endDate->format('M d, Y')
            ],
            'summary' => [
                'total_taps' => TapAnalytics::whereBetween('created_at', [$startDate, $endDate])->count(),
                'unique_businesses' => TapAnalytics::whereBetween('created_at', [$startDate, $endDate])
                    ->distinct('business_id')->count(),
                'suspicious_taps' => TapAnalytics::whereBetween('created_at', [$startDate, $endDate])
                    ->where('is_suspicious', true)->count()
            ],
            'top_performing_cards' => TapAnalytics::select('business_id', 'card_id', \DB::raw('count(*) as tap_count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('business_id', 'card_id')
                ->orderBy('tap_count', 'desc')
                ->limit(10)
                ->get(),
            'taps_by_source' => TapAnalytics::select('tap_source', \DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('tap_source')
                ->get(),
            'taps_by_device' => TapAnalytics::select('device_type', \DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('device_type')
                ->get(),
            'taps_by_country' => TapAnalytics::select('country', \DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get(),
            'daily_taps' => TapAnalytics::select(\DB::raw('DATE(created_at) as date'), \DB::raw('count(*) as count'))
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get()
        ];

        // Add business details to top performing cards
        foreach ($data['top_performing_cards'] as $card) {
            $business = Business::find($card->business_id);
            $card->business_name = $business ? $business->title : 'Unknown';
            $card->user_name = $business ? ($business->user ? $business->user->name : 'Unknown') : 'Unknown';
        }

        return $data;
    }

    /**
     * Send report email
     */
    private function sendReportEmail($admin, $reportData, $type)
    {
        $subject = ucfirst($type) . ' Tap Analytics Report - ' . $reportData['period']['start'] . ' to ' . $reportData['period']['end'];
        
        $emailData = [
            'admin' => $admin,
            'report' => $reportData,
            'type' => $type
        ];

        try {
            Mail::send('emails.tap_analytics_report', $emailData, function ($message) use ($admin, $subject) {
                $message->to($admin->email, $admin->name)
                        ->subject($subject);
            });

            $this->info("Report sent to {$admin->email}");
        } catch (\Exception $e) {
            $this->error("Failed to send report to {$admin->email}: " . $e->getMessage());
        }
    }
} 