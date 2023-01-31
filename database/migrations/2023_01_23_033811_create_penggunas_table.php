<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("akun_id");
            $table->foreignId("grup_id");
            $table->string("token")->unique();
            $table->enum("aktif", ["0", "1"])->default("0");
            $table->timestamps();
            $table->foreign("akun_id")->references("id")->on("akuns")->onUpdate("cascade");
            $table->foreign("grup_id")->references("id")->on("grups")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penggunas');
    }
}
