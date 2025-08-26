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
        // Add order column to the model
        Schema::table('categories', function (Blueprint $table) {
            $table->integer('sort_order');
        });

// Set default sort order (just copy ID to sort order)
        DB::statement('UPDATE categories SET sort_order = id');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
