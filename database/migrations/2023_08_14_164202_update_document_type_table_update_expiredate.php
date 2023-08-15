<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDocumentTypeTableUpdateExpiredate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_types', function (Blueprint $table) {
            $table->integer('expire')->default(1)->change();
            $table->string('expire_type', 8)->default('YEAR')->nullable();
            // $table->integer('month')->change();
            // $table->renameColumn('no', 'running_no');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
