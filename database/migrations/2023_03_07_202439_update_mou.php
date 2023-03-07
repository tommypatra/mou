<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateMou extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mous', function ($table) {
            $table->dropColumn('no_surat_internal');
            $table->dropColumn('no_surat_eksternal');
            $table->dropColumn('tgl');
            $table->dropForeign(['pihak_id']);
            $table->dropColumn('pihak_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
