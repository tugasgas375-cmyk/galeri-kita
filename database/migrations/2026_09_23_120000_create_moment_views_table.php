<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('moment_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('moment_id');
            $table->string('device_id', 64);
            $table->timestamp('created_at')->nullable();

            $table->unique(['moment_id', 'device_id']);

            $table->foreign('moment_id')
                ->references('id')
                ->on('moments')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('moment_views');
    }
};