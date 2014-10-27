<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateTodstoImagesTable
 *
 * Migration class that creates the image_crop_resizer table.
 * Table is used to hold the images data.
 */
class CreateTodstoImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('image_crop_resizer', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('image');
            $table->string('context');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('image_crop_resizer');
	}

}
