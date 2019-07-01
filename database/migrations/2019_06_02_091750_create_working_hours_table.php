<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkingHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'working_hours',
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('day');
                $table->time('opens_at');
                $table->time('closes_at');
                $table->integer('model_id');
                $table->string('model_type');
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_hours');
    }
}
