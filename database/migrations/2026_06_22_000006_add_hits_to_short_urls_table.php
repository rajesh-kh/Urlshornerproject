<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->unsignedBigInteger('hits')->default(0)->after('original_url');
        });
    }

    public function down()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->dropColumn('hits');
        });
    }
};
