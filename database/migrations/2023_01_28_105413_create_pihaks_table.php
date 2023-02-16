<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePihaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pihaks', function (Blueprint $table) {
            $table->id();
            $table->string("pihak", 100);
            $table->string("alamat", 250)->nullable();
            $table->foreignId("kabupaten_id");
            $table->foreignId("jenis_pihak_id");
            $table->timestamps();
            $table->foreign("kabupaten_id")->references("id")->on("kabupatens")->cascadeOnUpdate();
            $table->foreign("jenis_pihak_id")->references("id")->on("jenis_pihaks")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pihaks');
    }
}
