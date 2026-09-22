<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('deliveries', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('driver_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name'); $table->string('customer_phone', 30);
            $table->text('item_description'); $table->unsignedInteger('quantity')->nullable();
            $table->decimal('amount_due', 12, 2)->default(0);
            $table->text('delivery_address'); $table->string('district')->nullable();
            $table->string('landmark')->nullable(); $table->text('instructions')->nullable();
            $table->decimal('latitude', 10, 7)->nullable(); $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('scheduled_for')->index();
            $table->enum('status', ['pending','collected','waiting','on_tour','en_route','arrived_in_area','delivered','customer_absent','customer_unreachable','address_not_found','refused','rescheduled','cancelled'])->default('pending')->index();
            $table->text('status_reason')->nullable(); $table->decimal('collected_amount', 12, 2)->nullable();
            $table->string('payment_method')->nullable(); $table->timestamp('delivered_at')->nullable();
            $table->uuid('tracking_token')->unique(); $table->timestamp('tracking_expires_at')->nullable(); $table->timestamp('tracking_revoked_at')->nullable();
            $table->timestamps();
            $table->index(['store_id', 'scheduled_for']); $table->index(['driver_id', 'scheduled_for']);
        });
    }
    public function down(): void { Schema::dropIfExists('deliveries'); }
};
