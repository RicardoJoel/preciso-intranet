<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('code',20)->nullable();
            $table->string('name',100)->nullable();
            $table->date('start_at');
            $table->date('end_at');
            $table->boolean('start_ms');
            $table->boolean('end_ms');
            $table->string('status',20);
            $table->float('progress');
            $table->string('description',500)->nullable();
            $table->unsignedInteger('relevance');
            $table->unsignedInteger('duration');
            $table->unsignedInteger('level');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
