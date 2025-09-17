<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bayt_api_manager_mosques', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('district_id')->index();
            $table->unsignedBigInteger('province_id')->index();
            $table->string('name');
            $table->string('url')->nullable();
            $table->string('bomdod')->nullable();
            $table->string('xufton')->nullable();
            $table->string('has_location');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('altitude')->nullable();
            $table->string('distance')->nullable();
            $table->timestamps();
        });

        Schema::create('bayt_api_manager_mosque_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mosque_id')->index();
            $table->string('original_url');
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('mime_type')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index('original_url');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bayt_api_manager_mosque_images');
        Schema::dropIfExists('bayt_api_manager_mosques');
    }
};
