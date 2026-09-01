<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('room_availabilities', function (Blueprint $table) {
   $table->id(); $table->foreignId('room_type_id')->constrained()->cascadeOnDelete();
   $table->date('date'); $table->unsignedInteger('available_inventory');
   $table->decimal('price',12,2); $table->boolean('is_closed')->default(false);
   $table->unsignedTinyInteger('minimum_stay')->default(1); $table->timestamps();
   $table->unique(['room_type_id','date']);
  });
 }
 public function down(): void { Schema::dropIfExists('room_availabilities'); }
};