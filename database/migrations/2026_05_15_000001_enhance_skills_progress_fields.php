<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->unsignedTinyInteger('percentage')->default(70)->after('level');
            $table->unsignedInteger('display_order')->default(0)->after('icon');
            $table->boolean('is_active')->default(true)->after('display_order');
        });
    }

    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['percentage', 'display_order', 'is_active']);
        });
    }
};
