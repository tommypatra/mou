<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mous', function (Blueprint $table) {
            $table->id();
            $table->year("tahun");
            $table->text("tentang");
            $table->text("ruang_lingkup");
            $table->string("no_surat_internal", 150);
            $table->string("no_surat_eksternal", 150);
            $table->date("tgl")->nullable();
            $table->date("tgl_berlaku")->nullable();
            $table->date("tgl_berakhir")->nullable();
            $table->foreignId("pengguna_id");
            $table->foreignId("pihak_id");
            $table->foreignId("jenis_id");
            $table->foreignId("kategori_id");
            $table->timestamps();
            $table->foreign("pengguna_id")->references("id")->on("penggunas")->restrictOnDelete();
            $table->foreign("pihak_id")->references("id")->on("pihaks")->restrictOnDelete();
            $table->foreign("jenis_id")->references("id")->on("jenis")->restrictOnDelete();
            $table->foreign("kategori_id")->references("id")->on("kategoris")->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mous');
    }
}
