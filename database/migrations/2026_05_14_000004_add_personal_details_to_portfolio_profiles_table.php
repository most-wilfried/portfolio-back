<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolio_profiles', function (Blueprint $table) {
            $table->unsignedTinyInteger('age')->nullable()->after('full_name');
            $table->string('location')->nullable()->after('professional_title');
            $table->string('phone')->nullable()->after('email');
            $table->string('cv')->nullable()->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('portfolio_profiles', function (Blueprint $table) {
            $table->dropColumn(['age', 'location', 'phone', 'cv']);
        });
    }
};
