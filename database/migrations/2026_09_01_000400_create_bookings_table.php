<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('bookings', function (Blueprint $table) {
   $table->id(); $table->string('booking_number')->unique();
   $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
   $table->foreignId('property_id')->constrained()->cascadeOnDelete();
   $table->date('check_in'); $table->date('check_out'); $table->unsignedSmallInteger('nights');
   $table->string('status')->default('pending'); $table->decimal('total',12,2)->default(0); $table->timestamps();
  });
 }
 public function down(): void { Schema::dropIfExists('bookings'); }
};