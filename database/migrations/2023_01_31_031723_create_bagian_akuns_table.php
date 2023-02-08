<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBagianAkunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bagian_akuns', function (Blueprint $table) {
            $table->id();
            $table->foreignId("akun_id");
            $table->foreignId("bagian_id");
            $table->enum("aktif", ["0", "1"])->default("1");
            $table->timestamps();
            $table->unique(['akun_id', 'bagian_id']);
            $table->foreign("akun_id")->references("id")->on("akuns")->cascadeOnUpdate();
            $table->foreign("bagian_id")->references("id")->on("bagians")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bagian_akuns');
    }
}
