<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagiansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bagians', function (Blueprint $table) {
            $table->id();
            $table->string("bagian", 100);
            $table->foreignId("akun_id");
            $table->foreignId("bagian_id")->nullable();
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreign("akun_id")->references("id")->on("akuns")->onUpdate("cascade");
            $table->foreign("bagian_id")->references("id")->on("bagians")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bagians');
    }
}
