<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_code_hash')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->timestamp('otp_sent_at')->nullable();
            $table->unsignedTinyInteger('otp_attempts')->default(0);
        });

        // Keep all existing accounts verified.
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'otp_code_hash',
                'otp_expires_at',
                'otp_sent_at',
                'otp_attempts',
            ]);
        });
    }
};
