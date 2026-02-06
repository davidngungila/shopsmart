<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('quotations')) {
            Schema::table('quotations', function (Blueprint $table) {
                // Add total column if missing
                if (!Schema::hasColumn('quotations', 'total')) {
                    $table->decimal('total', 15, 2)->default(0)->after('tax');
                }
                
                // Add subtotal column if missing
                if (!Schema::hasColumn('quotations', 'subtotal')) {
                    $table->decimal('subtotal', 15, 2)->default(0)->after('warehouse_id');
                }
                
                // Add discount column if missing
                if (!Schema::hasColumn('quotations', 'discount')) {
                    $table->decimal('discount', 15, 2)->default(0)->after('subtotal');
                }
                
                // Add tax column if missing
                if (!Schema::hasColumn('quotations', 'tax')) {
                    $table->decimal('tax', 15, 2)->default(0)->after('discount');
                }
                
                // Add quotation_date if missing
                if (!Schema::hasColumn('quotations', 'quotation_date')) {
                    $table->date('quotation_date')->after('status');
                }
                
                // Add expiry_date if missing
                if (!Schema::hasColumn('quotations', 'expiry_date')) {
                    $table->date('expiry_date')->nullable()->after('quotation_date');
                }
                
                // Add terms_conditions if missing
                if (!Schema::hasColumn('quotations', 'terms_conditions')) {
                    $table->text('terms_conditions')->nullable()->after('expiry_date');
                }
                
                // Add customer_notes if missing
                if (!Schema::hasColumn('quotations', 'customer_notes')) {
                    $table->text('customer_notes')->nullable()->after('notes');
                }
                
                // Add is_sent if missing
                if (!Schema::hasColumn('quotations', 'is_sent')) {
                    $table->boolean('is_sent')->default(false)->after('customer_notes');
                }
                
                // Add sent_at if missing
                if (!Schema::hasColumn('quotations', 'sent_at')) {
                    $table->timestamp('sent_at')->nullable()->after('is_sent');
                }
                
                // Add converted_to_sale_id if missing
                if (!Schema::hasColumn('quotations', 'converted_to_sale_id')) {
                    if (Schema::hasTable('sales')) {
                        $table->foreignId('converted_to_sale_id')->nullable()->after('sent_at')->constrained('sales')->nullOnDelete();
                    } else {
                        $table->unsignedBigInteger('converted_to_sale_id')->nullable()->after('sent_at');
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop columns in down() to avoid data loss
        // If needed, columns can be dropped manually
    }
};

