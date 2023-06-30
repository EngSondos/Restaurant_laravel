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
        Schema::table('ingredients', function (Blueprint $table) {

            $table->decimal('quantity_per_item', 5, 2)->default(1);
            $table->decimal('quantity_used', 5, 2)->default(0);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {

            $table->dropColumn('quantity_per_item');
            $table->dropColumn('quantity_used');
                });
    }
};
