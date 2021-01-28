<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeSupportAndConfidenceType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE settings MODIFY confidence DOUBLE(20, 9) DEFAULT 0.0');
        DB::statement('ALTER TABLE settings MODIFY support DOUBLE(20, 9) DEFAULT 0.0');
        DB::statement('ALTER TABLE files MODIFY confidence DOUBLE(20, 9) DEFAULT 0.0');
        DB::statement('ALTER TABLE files MODIFY support DOUBLE(20, 9) DEFAULT 0.0');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('confidence')->change();
            $table->integer('support')->change();
        });

        Schema::table('files', function (Blueprint $table) {
            $table->integer('confidence')->change();
            $table->integer('support')->change();
        });
    }
}
