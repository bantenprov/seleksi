<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeleksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
	public function up()
	{
		Schema::create('seleksis', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('kegiatan_id');
			$table->dateTime('tanggal_seleksi')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
	public function down()
	{
		Schema::drop('seleksis');
	}
}
