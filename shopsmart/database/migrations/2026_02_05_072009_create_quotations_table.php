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
        if (!Schema::hasTable('quotations')) {
            Schema::create('quotations', function (Blueprint $table) {
                $table->id();
                $table->string('quotation_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('warehouse_id')->nullable()->constrained()->nullOnDelete();
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('discount', 15, 2)->default(0);
                $table->decimal('tax', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->enum('status', ['pending', 'approved', 'rejected', 'expired', 'converted'])->default('pending');
                $table->date('quotation_date');
                $table->date('expiry_date')->nullable();
                $table->text('terms_conditions')->nullable();
                $table->text('notes')->nullable();
                $table->text('customer_notes')->nullable();
                $table->boolean('is_sent')->default(false);
                $table->timestamp('sent_at')->nullable();
                $table->foreignId('converted_to_sale_id')->nullable()->constrained('sales')->nullOnDelete();
                $table->timestamps();
            });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
