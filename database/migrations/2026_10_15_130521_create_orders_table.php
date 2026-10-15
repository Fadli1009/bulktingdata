<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
            $table->foreignId('ordered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('package_name');
            $table->string('package_type');
            $table->unsignedBigInteger('total')->default(0);
            $table->string('status')->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
