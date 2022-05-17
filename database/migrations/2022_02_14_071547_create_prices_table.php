<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id('pk');
            $table->foreignIdFor(App\Models\Team::class)->index();
            $table->uuid('guid')->index();
            $table->uuid('commodity_guid');
            $table->uuid('currency_guid');
            $table->dateTime('date')->default('1970-01-01 00:00:00');
            $table->string('source', 2048)->nullable();
            $table->string('type', 2048)->nullable();
            $table->bigInteger('value_num');
            $table->bigInteger('value_denom');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prices');
    }
}
