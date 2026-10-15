<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->default('personal')->index();
            $table->unsignedBigInteger('price')->default(0);
            $table->string('billing_period')->default('monthly');
            $table->unsignedInteger('upload_limit')->nullable();
            $table->unsignedInteger('seat_limit')->nullable();
            $table->string('status')->default('active')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('badge')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->json('features')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
