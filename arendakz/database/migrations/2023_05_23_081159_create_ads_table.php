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
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('status', ['active','pending', 'inactive', 'rejected'])->default('pending');
            $table->unsignedBigInteger('category_id');
            $table->text('description');
            $table->string('photo')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('contact_phone');
            $table->string('contact_email');
            $table->timestamps();

            // Define foreign key constraint for category_id
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
