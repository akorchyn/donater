<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('referral_id');
        });
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropIndex('user_id');
            $table->dropIndex('referral_id');
        });
    }
};
