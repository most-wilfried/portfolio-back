<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('project_type')->default('personnel')->after('category');
            $table->unsignedSmallInteger('year')->nullable()->after('project_type');
            $table->string('status')->default('terminé')->after('year');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['project_type', 'year', 'status']);
        });
    }
};
