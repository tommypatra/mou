<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParaPihaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('para_pihaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("pihak_id");
            $table->foreignId("mou_id");
            $table->timestamps();
            $table->foreign("mou_id")->references("id")->on("mous")->cascadeOnUpdate();
            $table->foreign("pihak_id")->references("id")->on("pihaks")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('para_pihaks');
    }
}
