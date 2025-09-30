<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('help_sections', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // faq, guia, glosario, contacto, etc.
            $table->string('title')->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('help_sections');
    }
};
