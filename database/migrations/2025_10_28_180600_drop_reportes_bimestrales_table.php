<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('reportes_bimestrales');
    }

    public function down(): void
    {
        // No se recrea la tabla en down; se mantiene eliminada.
    }
};