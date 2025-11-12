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
        Schema::create('request_phone_changes', function (Blueprint $table) {
            $table->id(); // id int [pk, increment]
            $table->unsignedBigInteger('user_id'); // user_id bigint [ref: > users.id]
            $table->string('new_country_code', 5);
            $table->string('new_phone', 15);
            $table->string('otp', 6);
            $table->timestamp('otp_expired_at');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps(); // created_at & updated_at otomatis

            // Indexes
            $table->unique('id');
            $table->index('user_id');
            $table->index(['otp', 'is_verified']);

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_phone_changes');
    }
};
