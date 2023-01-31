<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->integer("urut")->nullable();
            $table->foreignId("grup_id");
            $table->foreignId("akun_id");
            $table->foreignId("modul_id");
            $table->foreignId("menu_id")->nullable();
            $table->timestamps();
            $table->foreign("grup_id")->references("id")->on("grups")->cascadeOnUpdate();
            $table->foreign("menu_id")->references("id")->on("menus")->cascadeOnUpdate();
            $table->foreign("akun_id")->references("id")->on("akuns")->cascadeOnUpdate();
            $table->foreign("modul_id")->references("id")->on("moduls")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
