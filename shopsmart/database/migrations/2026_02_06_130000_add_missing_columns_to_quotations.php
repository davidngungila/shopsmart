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
                // Add subtotal column if missing (add first, before other calculated fields)
                if (!Schema::hasColumn('quotations', 'subtotal')) {
                    $table->decimal('subtotal', 15, 2)->default(0);
                }
                
                // Add discount column if missing
                if (!Schema::hasColumn('quotations', 'discount')) {
                    $table->decimal('discount', 15, 2)->default(0);
                }
                
                // Add tax column if missing
                if (!Schema::hasColumn('quotations', 'tax')) {
                    $table->decimal('tax', 15, 2)->default(0);
                }
                
                // Add total column if missing
                if (!Schema::hasColumn('quotations', 'total')) {
                    $table->decimal('total', 15, 2)->default(0);
                }
                
                // Add quotation_date if missing
                if (!Schema::hasColumn('quotations', 'quotation_date')) {
                    $table->date('quotation_date');
                }
                
                // Add expiry_date if missing
                if (!Schema::hasColumn('quotations', 'expiry_date')) {
                    $table->date('expiry_date')->nullable();
                }
                
                // Add terms_conditions if missing
                if (!Schema::hasColumn('quotations', 'terms_conditions')) {
                    $table->text('terms_conditions')->nullable();
                }
                
                // Add customer_notes if missing
                if (!Schema::hasColumn('quotations', 'customer_notes')) {
                    $table->text('customer_notes')->nullable();
                }
                
                // Add is_sent if missing
                if (!Schema::hasColumn('quotations', 'is_sent')) {
                    $table->boolean('is_sent')->default(false);
                }
                
                // Add sent_at if missing
                if (!Schema::hasColumn('quotations', 'sent_at')) {
                    $table->timestamp('sent_at')->nullable();
                }
                
                // Add converted_to_sale_id if missing
                if (!Schema::hasColumn('quotations', 'converted_to_sale_id')) {
                    if (Schema::hasTable('sales')) {
                        $table->foreignId('converted_to_sale_id')->nullable()->constrained('sales')->nullOnDelete();
                    } else {
                        $table->unsignedBigInteger('converted_to_sale_id')->nullable();
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

