<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageCusAwsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_cus_aws', function (Blueprint $table) {
            $table->id();
            $table->integer('cus_id');
            $table->string('image_name')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamp('expire_date_at')->nullable();
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
        Schema::dropIfExists('image_cus_aws');
    }
}
