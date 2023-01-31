<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKabupatensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kabupatens', function (Blueprint $table) {
            $table->id();
            $table->string("kabupaten", 100);
            $table->foreignId("provinsi_id");
            $table->foreignId("akun_id");
            $table->timestamps();
            $table->foreign("akun_id")->references("id")->on("akuns")->cascadeOnUpdate();
            $table->foreign("provinsi_id")->references("id")->on("provinsis")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kabupatens');
    }
}
