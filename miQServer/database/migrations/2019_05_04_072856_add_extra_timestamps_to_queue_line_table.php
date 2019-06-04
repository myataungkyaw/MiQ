<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraTimestampsToQueueLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('queue_lines', function (Blueprint $table) {
            $table->datetime('started_at')->nullable()->comment('when start serving');
            $table->datetime('finished_at')->nullable();
            $table->datetime('called_at')->nullable()->comment('last call at');
            $table->integer('number_of_returnq')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('queue_lines', function (Blueprint $table) {
            $table->dropColumn('started_at');
            $table->dropColumn('finished_at');
            $table->dropColumn('called_at');
            $table->dropCoumn('number_of_returnq');
        });
    }
}
