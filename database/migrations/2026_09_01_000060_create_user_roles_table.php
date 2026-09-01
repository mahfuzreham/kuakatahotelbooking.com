<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
 public function up(): void {
  Schema::create('user_roles', function (Blueprint $table) {
   $table->id();
   $table->foreignId('user_id')->constrained()->cascadeOnDelete();
   $table->foreignId('role_id')->constrained()->cascadeOnDelete();
   // Vendor/property tables are created by later migrations, so keep these
   // references as indexed nullable IDs to avoid migration-order FK failures.
   $table->unsignedBigInteger('vendor_id')->nullable()->index();
   $table->unsignedBigInteger('property_id')->nullable()->index();
   $table->timestamps();
   $table->index(['user_id','role_id']);
  });
 }
 public function down(): void { Schema::dropIfExists('user_roles'); }
};