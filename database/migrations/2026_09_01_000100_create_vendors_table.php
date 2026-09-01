<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('vendors', function (Blueprint $table) {
   $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete();
   $table->string('business_name'); $table->string('status')->default('pending');
   $table->string('verification_status')->default('unverified'); $table->timestamps();
  });
 }
 public function down(): void { Schema::dropIfExists('vendors'); }
};