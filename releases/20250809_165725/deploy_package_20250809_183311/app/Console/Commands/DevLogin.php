<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DevLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:login {email?} {--password=password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Login as a specific user for development testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (app()->environment('production')) {
            $this->error('This command is only available in development environment.');
            return 1;
        }

        $email = $this->argument('email');
        
        if (!$email) {
            $users = User::take(5)->get(['id', 'email', 'email_verified_at']);
            $this->info('Available users:');
            foreach ($users as $user) {
                $verified = $user->email_verified_at ? '✓' : '✗';
                $this->line("  {$user->id}. {$user->email} {$verified}");
            }
            $email = $this->ask('Enter email to login as:');
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email '{$email}' not found.");
            return 1;
        }

        // Set a default password if not set
        if (empty($user->password)) {
            $password = $this->option('password');
            $user->password = Hash::make($password);
            $user->save();
            $this->info("Password set to: {$password}");
        }

        // Verify email if not verified
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
            $this->info("Email verified for: {$user->email}");
        }

        $this->info("Ready to login as: {$user->email}");
        $this->info("Password: {$this->option('password')}");
        $this->info("Email verified: " . ($user->email_verified_at ? 'Yes' : 'No'));
        
        return 0;
    }
}
