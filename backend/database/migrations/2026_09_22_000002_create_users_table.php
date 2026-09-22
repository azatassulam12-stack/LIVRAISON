<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['platform_admin', 'store_admin', 'driver'])->index();
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
        Schema::create('personal_access_tokens', function (Blueprint $table): void {
            $table->id(); $table->morphs('tokenable'); $table->string('name');
            $table->string('token', 64)->unique(); $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable(); $table->timestamp('expires_at')->nullable(); $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('personal_access_tokens'); Schema::dropIfExists('users'); }
};
