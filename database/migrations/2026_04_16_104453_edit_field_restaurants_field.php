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
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->decimal('avg_rating', 3, 2)->default(0)->nullable()->change();
            $table->boolean('is_approved')->default(false)->nullable()->change();
            $table->boolean('is_active')->default(true)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('address')->change();
            $table->string('phone')->change();
            $table->decimal('avg_rating', 3, 2)->default(0)->change();
            $table->boolean('is_approved')->default(false)->change();
            $table->boolean('is_active')->default(true)->change();
        });
    }
};
