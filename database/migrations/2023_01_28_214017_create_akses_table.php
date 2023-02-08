<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAksesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("menu_id");
            $table->enum("c", ["0", "1"])->default("0");
            $table->enum("r", ["0", "1"])->default("1");
            $table->enum("u", ["0", "1"])->default("0");
            $table->enum("d", ["0", "1"])->default("0");
            $table->enum("s", ["0", "1"])->default("0");
            $table->timestamps();
            $table->unique('menu_id');
            $table->foreign("menu_id")->references("id")->on("menus")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akses');
    }
}
