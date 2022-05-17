<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommoditiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commodities', function (Blueprint $table) {
            $table->id('pk');
            $table->foreignIdFor(App\Models\Team::class)->index();
            $table->uuid('guid')->index();
            $table->string('namespace', 2048);
            $table->string('mnemonic', 2048);
            $table->string('fullname', 2048)->nullable();
            $table->string('cusip', 2048)->nullable();
            $table->integer('fraction');
            $table->integer('quote_flag');
            $table->string('quote_source', 2048)->nullable();
            $table->string('quote_tz', 2048)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commodities');
    }
}
