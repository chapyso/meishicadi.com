
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_analytics', function (Blueprint $table) {
            $table->dropForeign(['card_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
EOF

cat > database/migrations/2025_07_21_120322_create_card_analytics_table.php << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_analytics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('card_id');
            $table->unsignedBigInteger('user_id');
            $table->string('source'); // QR, NFC, Wallet, Link, etc.
            $table->timestamp('tapped_at');
            $table->string('device_info')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_analytics');
    }
};
EOF

php artisan migrate
php artisan migrate:status
php artisan migrate --path=database/migrations/2025_07_21_120825_add_foreign_keys_to_card_analytics_table.php
echo '
    // Test analytics route
    Route::get("/test-analytics", function () {
        return response()->json([
            "message" => "Analytics system is working!",
            "user_has_premium" => auth()->user() ? auth()->user()->hasPremiumAnalytics() : false
        ]);
    });' >> routes/web.php
php artisan make:migration add_analytics_to_plans_table
cat > database/migrations/2025_07_21_120915_add_analytics_to_plans_table.php << 'EOF'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('enable_analytics')->default(false)->after('enable_wallet');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('enable_analytics');
        });
    }
};
EOF

php artisan migrate
php artisan migrate --path=database/migrations/2025_07_21_120915_add_analytics_to_plans_table.php
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/test-analytics
tail -20 storage/logs/laravel.log
tail -50 storage/logs/laravel.log | grep -A 10 -B 5 "local.ERROR" | tail -20
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/
ps aux | grep "php artisan serve" | grep -v grep
curl -s http://127.0.0.1:8000/ | head -5
ls -la
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
chmod -R 775 storage bootstrap/cache
php artisan key:generate
tail -40 storage/logs/laravel.log
tail -100 storage/logs/laravel.log | grep -A 5 -B 5 'local.ERROR'
cat -n app/Models/Business.php | tail -40
cat > app/Models/Business.php << 'EOF'
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

    // ... (other methods and attributes) ...

    public function walletPasses()
    {
        return $this->hasMany(WalletPass::class);
    }

    public function activeWalletPasses()
    {
        return $this->hasMany(WalletPass::class)->where('is_active', true);
    }

    public function hasWalletPasses(): bool
    {
        return $this->walletPasses()->where('is_active', true)->exists();
    }

    public function appleWalletPass()
    {
        return $this->hasOne(WalletPass::class)->where('wallet_type', 'apple')->where('is_active', true);
    }

    public function googleWalletPass()
    {
        return $this->hasOne(WalletPass::class)->where('wallet_type', 'google')->where('is_active', true);
    }

    /**
     * Get the analytics for this business card.
     */
    public function analytics()
    {
        return $this->hasMany(CardAnalytics::class, "card_id");
    }
}
EOF

tail -40 storage/logs/laravel.log
tail -100 storage/logs/laravel.log
cat -n app/Models/User.php | tail -40
cat > app/Models/User.php << 'EOF'
<?php

namespace App\Models;
use App\Models\Business;
use App\Models\PlanOrder;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\UserEmailTemplate;
use Auth;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'type',
        'avatar',
        'lang',
        'current_business',
        'delete_status',
        'plan',
        'plan_expire_date',
        'is_enable_login',
        'created_by',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // ... (other methods and attributes) ...

    public function walletPasses()
    {
        return $this->hasMany(WalletPass::class);
    }

    public function hasWalletAccess(): bool
    {
        $currentPlan = $this->currentPlan();
        return $currentPlan && ($currentPlan->enable_wallet ?? false);
    }

    /**
     * Get the card analytics for this user.
     */
    public function cardAnalytics()
    {
        return $this->hasMany(CardAnalytics::class);
    }

    /**
     * Check if user has premium analytics access.
     */
    public function hasPremiumAnalytics(): bool
    {
        $currentPlan = $this->currentPlan();
        return $currentPlan && ($currentPlan->enable_analytics ?? false);
    }
}
EOF

php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
chmod -R 775 storage bootstrap/cache
cat .env
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000
cd ./domains/meishicadi.com/public_html && curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000
curl -v http://127.0.0.1:8000 2>&1 | head -20
tail -20 storage/logs/laravel.log
tail -50 storage/logs/laravel.log | grep -A 10 -B 5 "ERROR\|Exception\|Error"
ls -la storage/logs/ && tail -100 storage/logs/laravel.log | head -50
cat app/Http/Middleware/VerifyCsrfToken.php
sed -i "s/'cookie_consent'/'cookie_consent'/g" app/Http/Middleware/VerifyCsrfToken.php
cat app/Http/Middleware/VerifyCsrfToken.php
sed -i "s/cookie_consent/'cookie_consent'/g" app/Http/Middleware/VerifyCsrfToken.php && sed -i "s/card_cookie_consent/'card_cookie_consent'/g" app/Http/Middleware/VerifyCsrfToken.php
cat app/Http/Middleware/VerifyCsrfToken.php
cp app/Http/Middleware/VerifyCsrfToken.php app/Http/Middleware/VerifyCsrfToken.php.backup
cat > app/Http/Middleware/VerifyCsrfToken.php << 'EOF'
<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        'plan/paytm/*',
        'plan-pay-with-paytm/*',
        'plan-pay-with-paymentwall/*',
        'paymentwall/*',
        'iyzipay/callback/*',
        'plan-paytab-success/',
        '/aamarpay*',
        'cookie_consent',
        'card_cookie_consent',
    ];
}
EOF

cat app/Http/Middleware/VerifyCsrfToken.php
grep -n "function edit" app/Http/Controllers/BusinessController.php
sed -n '135,145p' app/Http/Controllers/BusinessController.php
grep -r "BusinessController.*edit" routes/
ls -la app/Models/Business.php
sed -n '135,145p' app/Http/Controllers/BusinessController.php
sed -i 's/public function edit(Business $business, $id)/public function edit($id)/' app/Http/Controllers/BusinessController.php
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000
tail -20 storage/logs/laravel.log
tail -100 storage/logs/laravel.log | grep -A 5 -B 5 "ERROR\|Exception\|Error" | tail -20
sed -n '135,145p' app/Http/Controllers/BusinessController.php
php artisan config:clear && php artisan cache:clear && php artisan route:clear
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000
curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/login
tail -50 storage/logs/laravel.log | grep -A 3 -B 3 "ERROR\|Exception\|Error" | tail -10
ps aux | grep "php artisan serve" | grep -v grep
curl -s http://127.0.0.1:8000/login | head -10
tail -50 storage/logs/laravel.log
tail -100 storage/logs/laravel.log | grep -A 10 -B 5 "ERROR\|Exception\|Error" | tail -30
tail -200 storage/logs/laravel.log | grep -A 5 -B 5 "local.ERROR" | tail -20
sed -n '135,145p' app/Http/Controllers/BusinessController.php
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
find . -name "email.svg" -type f
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd "/Users/ronaldkyambadde/Desktop/MEISHICADI FILES/MEISHICADI" && pwd
cd "/Users/ronaldkyambadde/Desktop/MEISHICADI FILES/MEISHICADI" && ls -la
find . -name "artisan" -type f 2>/dev/null | head -10
cd ./domains/meishicadi.com/public_html && ls -la
cat .env
php --version && composer --version
ls -la vendor/ | head -5
php artisan storage:link
sleep 3 && curl -I http://localhost:8000
php artisan serve --host=127.0.0.1 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd "/Users/ronaldkyambadde/Desktop/Meishi cadi/u916293666.20250718025353/domains/meishicadi.com/public_html" && php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd "/home/u916293666/Desktop/Meishi cadi/u916293666.20250718025353/domains/meishicadi.com/public_html" && php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd "Users/ronaldkyambadde/Desktop/Meishi cadi/u916293666.20250718025353/domains/meishicadi.com/public_html" && php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd /home/u916293666/domains/meishicadi.com/public_html && php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd domains/meishicadi.com/public_html && php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
cd "/Users/ronaldkyambadde/Desktop/Meishi cadi/u916293666.20250718025353/domains/meishicadi.com/public_html" && php artisan serve --host=0.0.0.0 --port=8000
. "/home/u916293666/.cursor-server/bin/a8e95743c5268be73767c46944a71f4465d05c90/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
ls -l "/Users/ronaldkyambadde/Desktop/Meishi cadi/u916293666.20250718025353/domains/meishicadi.com/public_html"
php -v
lsof -i :8000 | grep LISTEN
cat /Users/ronaldkyambadde/Desktop/Meishi\ cadi/u916293666.20250718025353/domains/meishicadi.com/public_html/storage/logs/laravel.log | tail -n 30
ls -l ~/Desktop
ls -l
ls -l domains
ls -l domains/meishicadi.com
ls -l domains/meishicadi.com/public_html
cat domains/meishicadi.com/public_html/storage/logs/laravel.log | tail -n 40
cat domains/meishicadi.com/public_html/storage/logs/laravel.log | tail -n 100
cd domains/meishicadi.com/public_html && php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
ls -l app/Models
tail -n 40 storage/logs/laravel.log
tail -n 60 storage/logs/laravel.log
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear
tail -n 60 storage/logs/laravel.log
   pwd
   ls -lah
   php artisan config:cache
   php artisan view:cache
   php artisan route:cache
   php artisan cache:clear
   ssh -p 65002 u916293666@148.135.129.127
   cd public_html
   ls -lah
   php artisan config:cache
   php artisan view:cache
   php artisan route:cache
   php artisan cache:clear
   ssh -p 65002 u916293666@148.135.129.127
   ls -lah domains
   ls -lah domains/japytag.us
   cd /home/u916293666/domains/japytag.us/public_html
   php artisan config:cache
   php artisan view:cache
   php artisan route:cache
   php artisan cache:clear
tail -n 50 storage/logs/laravel.log
grep -rn "password.email" routes/
php artisan config:cache
php artisan view:cache
php artisan route:cache
php artisan cache:clear
tail -n 50 storage/logs/laravel.log
   cd domains/japytag.us/public_html
   php artisan route:cache
ls -lah domains
ls -lah domains/japytag.us
   grep -rn "password.reset" routes/
   php artisan route:cache
   grep -rn "password.reset" routes/
Abantu@256
cat /etc/hosts
exit
php --version
ls -la
ssh -p 65002 u916293666@148.135.129.127
cd ~ && ls -la
ls -la domains/
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127 "pwd && ls -la"
ls ~
ls -la /home/u916293666/domains/meishicadi.com/public_html
cd /home/u916293666/domains/meishicadi.com/public_html && php artisan make:migration add_tap_count_to_cards_table --table=cards
nano database/migrations/2025_07_24_165654_add_tap_count_to_cards_table.php
ssh -p 65002 u916293666@148.135.129.127 "cd domains/meishicadi.com/Meishicadi && pwd && ls -la"
mysqldump -u root -p --all-databases > full_database_backup.sql
ssh -p 65002 u916293666@148.135.129.127
cd domains/meishicadi.com/Meishicadi && pwd && ls -la
ls -la && find . -name "*meishicadi*" -type d
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127 "ls -la"
\
ssh -p 65002 u916293666@148.135.129.127 "cd meishicadi_website && ls -la"
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127 "ls -la"
ssh -p 65002 u916293666@148.135.129.127 "ls -la meishicadi_website/"
ssh -p 65002 u916293666@148.135.129.127 "ls -la public_html/"
ssh -p 65002 u916293666@148.135.129.127 "ls -la public_html/public_html/"
ssh -p 65002 u916293666@148.135.129.127 "find public_html/public_html/resources -name '*color*' -o -name '*wheel*' -o -name '*picker*'"
ssh -p 65002 u916293666@148.135.129.127 "find public_html/public_html/public -name '*color*' -o -name '*wheel*' -o -name '*picker*'"
ssh -p 65002 u916293666@148.135.129.127 "find meishicadi_website -name '*color*' -o -name '*wheel*' -o -name '*picker*'"
ssh -p 65002 u916293666@148.135.129.127 "rm -f public_html/public_html/public/assets/js/custom/temp_color_code.js"
ssh -p 65002 u916293666@148.135.129.127 "rm -f public_html/public_html/public/assets/img/vcard24/color-plat.png public_html/public_html/public/assets/img/vcard24/pencil-color.png"
ssh -p 65002 u916293666@148.135.129.127 "rm -f public_html/public_html/public/assets/img/vcard24/._color-plat.png public_html/public_html/public/assets/img/vcard24/._pencil-color.png"
ssh -p 65002 u916293666@148.135.129.127 "find public_html/public_html/public -name '*color*' -o -name '*wheel*' -o -name '*picker*'"
ssh -p 65002 u916293666@148.135.129.127 "grep -r 'temp_color_code' public_html/public_html/resources/ public_html/public_html/public/ 2>/dev/null || echo 'No references found'"
ssh -p 65002 u916293666@148.135.129.127
sh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la | grep -i backup"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la"
sh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la"
scp -P 65002 -r u916293666@148.135.129.127:/home/u916293666/domains/meishicadi.com/public_html/resources/views/dashboard/ ./backup_dashboard/
ssh -p 65002 u916293666@148.135.129.127 "cd /home/u916293666/domains/meishicadi.com/ && pwd && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd /home/u916293666/domains/meishicadi.com/ && pwd && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd domains && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd domains/meishicadi.com && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd domains/meishicadi.com/public_html && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd domains/meishicadi.com/public_html/storage && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd domains/meishicadi.com/public_html/storage/uploads && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd domains/meishicadi.com/public_html/storage/uploads/avatar && ls -la"
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127 "pwd && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "find . -name 'public' -type d | head -5"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la uploads/avatar/"
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127
ssh meishi-ssh
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la && echo '--- Checking Laravel Structure ---' && find . -name 'artisan' -o -name 'composer.json' -o -name '*.log' 2>/dev/null"
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la && echo '--- Checking Laravel Structure ---' && find . -name 'artisan' -o -name 'composer.json' -o -name '*.log' 2>/dev/null"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la && echo '--- Main Laravel Files ---' && ls -la artisan composer.json .env 2>/dev/null || echo 'Some files not found'"
cd domains/japytag.us/public_html
ssh -p 65002 u916293666@148.135.129.127 "ls -la && pwd"
Abantu@256
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la && echo '--- Laravel Logs ---' && tail -n 50 storage/logs/laravel.log 2>/dev/null || echo 'No Laravel log found'"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la && echo '--- Checking for Laravel files ---' && find . -name '*.log' -o -name 'artisan' -o -name 'composer.json' 2>/dev/null"
ssh -p 65002 u916293666@148.135.129.127 "cd /home/u916293666 && ls -la | grep japy"
ssh -p 65002 u916293666@148.135.129.127
pwd && ls -la
ssh -p 65002 u916293666@148.135.129.127
pwd && ls -la
tail -50 error_log
ls -la storage/logs/
ssh -p 65002 u916293666@148.135.129.127
tail -100 storage/logs/laravel.log
tail -n 100 storage/logs/laravel.log | cat
ssh -p 65002 u916293666@148.135.129.127 "ls -la /home/u916293666/public_html/"
scp -P 65002 japytag_deploy_20250812_163947.tar.gz u916293666@148.135.129.127:/home/u916293666/
. "/home/u916293666/.cursor-server/bin/de327274300c6f38ec9f4240d11e82c3b0660b20/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
Abantu@256
run the app 
proceed 
run
   ssh -p 65002 u916293666@148.135.129.127
Abantu@256
curl -I https://meishicadi.com/
ls -la
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127
cat app/Models/Utility.php | sed -n '1400,1450p'
ssh -p 65002 u916293666@148.135.129.127
sed -n '1400,1450p' app/Models/Utility.php
sed -n '1340,1350p' app/Models/Utility.php
find . -name "Utility.php" -path "*/Models/*" 2>/dev/null
sed -n '1340,1350p' domains/meishicadi.com/public_html/app/Models/Utility.php
ssh -p 65002 u916293666@148.135.129.127
sed -i '1342a\        $isenable = '\''0'\'';' domains/meishicadi.com/public_html/app/Models/Utility.php
sed -n '1340,1350p' domains/meishicadi.com/public_html/app/Models/Utility.php
curl -I https://meishicadi.com/business/264/edit
ssh -p 65002 u916293666@148.135.129.127
cd public_html && pwd
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la resources/views/card/theme5/"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && find . -name 'index.blade.php' -path '*/theme5/*'"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && find . -name '*.blade.php' | head -10"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && find . -type d -name 'resources'"
ssh -p 65002 u916293666@148.135.129.127 "cd public_html && ls -la | grep -E '(laravel|app|resources)'"
sed -n '290,310p' domains/meishicadi.com/public_html/resources/views/card/theme5/index.blade.php
ssh -p 65002 u916293666@148.135.129.127
sed -n '295,305p' domains/meishicadi.com/public_html/resources/views/card/theme5/index.blade.php
. "/home/u916293666/.cursor-server/bin/d750e54bba5cffada6d7b3d18e5688ba5e944ad0/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
. "/home/u916293666/.cursor-server/bin/a9c77ceae65b77ff772d6adfe05f24d8ebcb2790/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
ssh -p 65002 u916293666@148.135.129.127
ssh -p 65002 u916293666@148.135.129.127
. "/home/u916293666/.cursor-server/bin/a9c77ceae65b77ff772d6adfe05f24d8ebcb2790/out/vs/workbench/contrib/terminal/common/scripts/shellIntegration-bash.sh"
