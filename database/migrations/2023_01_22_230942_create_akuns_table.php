<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAkunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akuns', function (Blueprint $table) {
            $table->id();
            $table->string("nama", 200);
            $table->string("email", 200)->unique();
            $table->string("password");
            $table->string("glrdepan", 50)->nullable();
            $table->string("glrbelakang", 50)->nullable();
            $table->enum("kel", ["L", "P"]);
            $table->string("tempatlahir", 50)->nullable();
            $table->date("tanggallahir")->nullable();
            $table->text("alamat")->nullable();
            $table->string("nohp", 50)->nullable();
            $table->string("foto")->default('images/user-avatar.png');
            $table->enum("aktif", ["0", "1"])->default("1");
            $table->timestamps();
            $table->index('kel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('akuns');
    }
}
