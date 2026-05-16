<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            if (! Schema::hasColumn('skills', 'color')) {
                $table->string('color', 32)->nullable()->after('icon');
            }
        });

        Schema::table('experiences', function (Blueprint $table) {
            if (! Schema::hasColumn('experiences', 'technologies')) {
                $table->json('technologies')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            if (Schema::hasColumn('experiences', 'technologies')) {
                $table->dropColumn('technologies');
            }
        });

        Schema::table('skills', function (Blueprint $table) {
            if (Schema::hasColumn('skills', 'color')) {
                $table->dropColumn('color');
            }
        });
    }
};
