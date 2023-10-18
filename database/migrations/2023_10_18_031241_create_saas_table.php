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
        Schema::create('saas', function (Blueprint $table) {
            $table->id();
            $table->string('client');
            $table->string('client_name');
            $table->uuid('hostname_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('hostname_id')->references('id')->on('hostnames')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saas');
    }
};
