<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameColumnToImagesTable extends Migration
{
    public function up(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->string('name');
        });
    }

    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}
