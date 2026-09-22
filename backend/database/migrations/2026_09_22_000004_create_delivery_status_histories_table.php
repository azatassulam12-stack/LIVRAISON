<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('delivery_status_histories', function (Blueprint $table): void {
            $table->id(); $table->uuid('delivery_id'); $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status')->nullable(); $table->string('to_status'); $table->text('reason')->nullable(); $table->timestamp('created_at')->useCurrent();
            $table->foreign('delivery_id')->references('id')->on('deliveries')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('delivery_status_histories'); }
};
