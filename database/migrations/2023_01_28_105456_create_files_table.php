<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string("source", 250);
            $table->enum("is_file", ["0", "1"])->default("1");
            $table->enum("is_image", ["0", "1"])->default("1");
            $table->foreignId("pengguna_id");
            $table->foreignId("mou_id");
            $table->timestamps();
            $table->foreign("pengguna_id")->references("id")->on("penggunas")->cascadeOnUpdate();
            $table->foreign("mou_id")->references("id")->on("mous")->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
