<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCompaniesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('background_image')->nullable();
            $table->string('logo')->nullable();
            $table->integer('log_retention_period')->default(90);
            $table->string('queue_prefix')->nullable();
            $table->text('note')->nullable();
            $table->integer('third_party_integration')->default(0);
            $table->string('license_key')->nullable();
            $table->datetime('last_sync')->nullable();
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
        Schema::drop('companies');
    }
}
