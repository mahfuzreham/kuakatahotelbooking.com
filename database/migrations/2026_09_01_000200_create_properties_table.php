<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('properties', function (Blueprint $table) {
   $table->id(); $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
   $table->string('name'); $table->string('slug')->unique(); $table->string('type')->default('hotel');
   $table->string('status')->default('draft'); $table->text('address')->nullable();
   $table->string('city')->default('Kuakata'); $table->string('country')->default('Bangladesh'); $table->timestamps();
  });
 }
 public function down(): void { Schema::dropIfExists('properties'); }
};