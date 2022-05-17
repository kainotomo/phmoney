<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_amounts', function (Blueprint $table) {
            $table->id('pk');
            $table->foreignIdFor(App\Models\Team::class)->index();
            $table->bigInteger('id');
            $table->uuid('budget_guid');
            $table->uuid('account_guid');
            $table->integer('period_num');
            $table->bigInteger('amount_num');
            $table->bigInteger('amount_denom');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_amounts');
    }
}
