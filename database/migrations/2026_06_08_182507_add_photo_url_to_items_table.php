<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // database/migrations/xxxx_add_photo_url_to_items_table.php

public function up()
{
    Schema::table('items', function (Blueprint $table) {
        $table->string('photo_url')->nullable()->after('contact');
    });
}

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('photo_url');
        });
    }
};