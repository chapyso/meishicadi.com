<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add indexes for businesses table
        Schema::table('businesses', function (Blueprint $table) {
            // Index on created_by for user queries
            if (!$this->hasIndex('businesses', 'businesses_created_by_index')) {
                $table->index('created_by', 'businesses_created_by_index');
            }
            // Index on admin_enable for filtering
            if (!$this->hasIndex('businesses', 'businesses_admin_enable_index')) {
                $table->index('admin_enable', 'businesses_admin_enable_index');
            }
            // Composite index for subdomain lookups
            if (!$this->hasIndex('businesses', 'businesses_subdomain_enable_index')) {
                $table->index(['subdomain', 'enable_subdomain'], 'businesses_subdomain_enable_index');
            }
        });
        
        // Index on slug for fast lookups (using prefix length for TEXT column)
        if (Schema::hasTable('businesses') && !$this->hasIndex('businesses', 'businesses_slug_index')) {
            $connection = Schema::getConnection();
            $database = $connection->getDatabaseName();
            DB::statement("CREATE INDEX businesses_slug_index ON {$database}.businesses (slug(191))");
        }

        // Add indexes for business_hours
        Schema::table('business_hours', function (Blueprint $table) {
            if (!$this->hasIndex('business_hours', 'business_hours_business_id_index')) {
                $table->index('business_id', 'business_hours_business_id_index');
            }
        });

        // Add indexes for appoinments
        if (Schema::hasTable('appoinments')) {
            Schema::table('appoinments', function (Blueprint $table) {
                if (!$this->hasIndex('appoinments', 'appoinments_business_id_index')) {
                    $table->index('business_id', 'appoinments_business_id_index');
                }
            });
        }

        // Add indexes for services
        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                if (!$this->hasIndex('services', 'services_business_id_index')) {
                    $table->index('business_id', 'services_business_id_index');
                }
            });
        }

        // Add indexes for testimonials
        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                if (!$this->hasIndex('testimonials', 'testimonials_business_id_index')) {
                    $table->index('business_id', 'testimonials_business_id_index');
                }
            });
        }

        // Add indexes for contact_info
        if (Schema::hasTable('contact_info')) {
            Schema::table('contact_info', function (Blueprint $table) {
                if (!$this->hasIndex('contact_info', 'contact_info_business_id_index')) {
                    $table->index('business_id', 'contact_info_business_id_index');
                }
            });
        }

        // Add indexes for socials
        if (Schema::hasTable('socials')) {
            Schema::table('socials', function (Blueprint $table) {
                if (!$this->hasIndex('socials', 'socials_business_id_index')) {
                    $table->index('business_id', 'socials_business_id_index');
                }
            });
        }

        // Add indexes for galleries
        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                if (!$this->hasIndex('galleries', 'galleries_business_id_index')) {
                    $table->index('business_id', 'galleries_business_id_index');
                }
            });
        }

        // Add indexes for products
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!$this->hasIndex('products', 'products_business_id_index')) {
                    $table->index('business_id', 'products_business_id_index');
                }
            });
        }

        // Add indexes for pixel_fields
        if (Schema::hasTable('pixel_fields')) {
            Schema::table('pixel_fields', function (Blueprint $table) {
                if (!$this->hasIndex('pixel_fields', 'pixel_fields_business_id_index')) {
                    $table->index('business_id', 'pixel_fields_business_id_index');
                }
            });
        }

        // Add indexes for businessqrs
        if (Schema::hasTable('businessqrs')) {
            Schema::table('businessqrs', function (Blueprint $table) {
                if (!$this->hasIndex('businessqrs', 'businessqrs_business_id_index')) {
                    $table->index('business_id', 'businessqrs_business_id_index');
                }
            });
        }

        // Add indexes for visitor table
        if (Schema::hasTable('visitor')) {
            Schema::table('visitor', function (Blueprint $table) {
                if (!$this->hasIndex('visitor', 'visitor_created_by_index')) {
                    $table->index('created_by', 'visitor_created_by_index');
                }
            });
            
            // Index on slug for visitor table (using prefix length for TEXT column)
            if (!$this->hasIndex('visitor', 'visitor_slug_index')) {
                $connection = Schema::getConnection();
                $database = $connection->getDatabaseName();
                DB::statement("CREATE INDEX visitor_slug_index ON {$database}.visitor (slug(191))");
            }
        }

        // Add indexes for settings table
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!$this->hasIndex('settings', 'settings_created_by_name_index')) {
                    $table->index(['created_by', 'name'], 'settings_created_by_name_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropIndex('businesses_slug_index');
            $table->dropIndex('businesses_created_by_index');
            $table->dropIndex('businesses_admin_enable_index');
            $table->dropIndex('businesses_subdomain_enable_index');
        });

        Schema::table('business_hours', function (Blueprint $table) {
            $table->dropIndex('business_hours_business_id_index');
        });

        if (Schema::hasTable('appoinments')) {
            Schema::table('appoinments', function (Blueprint $table) {
                $table->dropIndex('appoinments_business_id_index');
            });
        }

        if (Schema::hasTable('services')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropIndex('services_business_id_index');
            });
        }

        if (Schema::hasTable('testimonials')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropIndex('testimonials_business_id_index');
            });
        }

        if (Schema::hasTable('contact_info')) {
            Schema::table('contact_info', function (Blueprint $table) {
                $table->dropIndex('contact_info_business_id_index');
            });
        }

        if (Schema::hasTable('socials')) {
            Schema::table('socials', function (Blueprint $table) {
                $table->dropIndex('socials_business_id_index');
            });
        }

        if (Schema::hasTable('galleries')) {
            Schema::table('galleries', function (Blueprint $table) {
                $table->dropIndex('galleries_business_id_index');
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_business_id_index');
            });
        }

        if (Schema::hasTable('pixel_fields')) {
            Schema::table('pixel_fields', function (Blueprint $table) {
                $table->dropIndex('pixel_fields_business_id_index');
            });
        }

        if (Schema::hasTable('businessqrs')) {
            Schema::table('businessqrs', function (Blueprint $table) {
                $table->dropIndex('businessqrs_business_id_index');
            });
        }

        if (Schema::hasTable('visitor')) {
            Schema::table('visitor', function (Blueprint $table) {
                $table->dropIndex('visitor_slug_index');
                $table->dropIndex('visitor_created_by_index');
            });
        }

        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropIndex('settings_created_by_name_index');
            });
        }
    }

    /**
     * Check if an index exists on a table
     */
    private function hasIndex($table, $indexName)
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? 
             AND table_name = ? 
             AND index_name = ?",
            [$database, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
};