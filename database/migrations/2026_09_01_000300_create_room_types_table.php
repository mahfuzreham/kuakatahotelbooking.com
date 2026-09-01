<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('room_types', function (Blueprint $table) {
   $table->id(); $table->foreignId('property_id')->constrained()->cascadeOnDelete();
   $table->string('name'); $table->unsignedTinyInteger('capacity')->default(2);
   $table->decimal('base_price',12,2); $table->unsignedInteger('inventory')->default(1); $table->timestamps();
  });
 }
 public function down(): void { Schema::dropIfExists('room_types'); }
};