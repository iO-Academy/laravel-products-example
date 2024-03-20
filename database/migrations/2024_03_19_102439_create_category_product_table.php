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
        // When making a link table, it MUST be named based on the following rules
        // - the singular names of the two tables you're relating
        // - in alphabetical order
        // - seperated with an underscore
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            // Create a foreignId for each table with the singular name of the table + _id
            $table->foreignId('category_id');
            $table->foreignId('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
};
