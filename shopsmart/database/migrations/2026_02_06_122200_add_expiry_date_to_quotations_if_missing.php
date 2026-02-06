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
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'quotation_date')) {
                $table->date('quotation_date')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'terms_conditions')) {
                $table->text('terms_conditions')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'customer_notes')) {
                $table->text('customer_notes')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'is_sent')) {
                $table->boolean('is_sent')->default(false);
            }
            if (!Schema::hasColumn('quotations', 'sent_at')) {
                $table->timestamp('sent_at')->nullable();
            }
            if (!Schema::hasColumn('quotations', 'converted_to_sale_id')) {
                if (Schema::hasTable('sales')) {
                    $table->foreignId('converted_to_sale_id')->nullable()->constrained('sales')->nullOnDelete();
                } else {
                    $table->unsignedBigInteger('converted_to_sale_id')->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $columnsToDrop = [];
            
            if (Schema::hasColumn('quotations', 'quotation_date')) {
                $columnsToDrop[] = 'quotation_date';
            }
            if (Schema::hasColumn('quotations', 'expiry_date')) {
                $columnsToDrop[] = 'expiry_date';
            }
            if (Schema::hasColumn('quotations', 'terms_conditions')) {
                $columnsToDrop[] = 'terms_conditions';
            }
            if (Schema::hasColumn('quotations', 'customer_notes')) {
                $columnsToDrop[] = 'customer_notes';
            }
            if (Schema::hasColumn('quotations', 'is_sent')) {
                $columnsToDrop[] = 'is_sent';
            }
            if (Schema::hasColumn('quotations', 'sent_at')) {
                $columnsToDrop[] = 'sent_at';
            }
            if (Schema::hasColumn('quotations', 'converted_to_sale_id')) {
                try {
                    $table->dropForeign(['converted_to_sale_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                $columnsToDrop[] = 'converted_to_sale_id';
            }
            
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};

