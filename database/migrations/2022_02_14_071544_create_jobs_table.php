<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id('pk');
            $table->foreignIdFor(App\Models\Team::class)->index();
            $table->uuid('guid')->index();
            $table->string('id', 2048);
            $table->string('name', 2048);
            $table->string('reference', 2048);
            $table->boolean('active');
            $table->integer('owner_type')->nullable();
            $table->uuid('owner_guid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
