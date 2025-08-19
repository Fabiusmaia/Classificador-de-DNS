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
        Schema::create('dns_logs', function (Blueprint $table) {
            $table->id();
            $table->string('domain');              
            $table->ipAddress('client_ip');        
            $table->timestamp('queried_at');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('classification')->nullable(); 
            $table->timestamps();                  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dns_logs');
    }
};
