<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('parte', function (Blueprint $t) {
            $t->unsignedBigInteger('ID_libro')->nullable()->index()->after('ID_parte');
        });
        Schema::table('tema', function (Blueprint $t) {
            $t->unsignedBigInteger('ID_parte')->nullable()->index()->after('ID_tema');
        });
        Schema::table('titulo', function (Blueprint $t) {
            $t->unsignedBigInteger('ID_tema')->nullable()->index()->after('ID_titulo');
        });
    }
    public function down(): void {
        Schema::table('parte', function (Blueprint $t) { $t->dropColumn('ID_libro'); });
        Schema::table('tema', function (Blueprint $t) { $t->dropColumn('ID_parte'); });
        Schema::table('titulo', function (Blueprint $t) { $t->dropColumn('ID_tema'); });
    }
};

