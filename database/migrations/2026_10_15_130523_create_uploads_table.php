<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->string('original_name');
            $table->string('path');
            $table->unsignedBigInteger('size')->default(0);
            $table->string('status')->default('pending')->index();
            $table->unsignedInteger('rows_processed')->nullable();
            $table->timestamps();

            $table->index(['owner_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
