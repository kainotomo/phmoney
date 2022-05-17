<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id('pk');
            $table->foreignIdFor(config('phmoney.foreign_id_model'), 'team_id')->index();
            $table->uuid('guid')->index();
            $table->string('name', 2048);
            $table->string('id', 2048);
            $table->string('notes', 2048);
            $table->uuid('currency');
            $table->boolean('active');
            $table->boolean('tax_override');
            $table->string('addr_name', 1024)->nullable();
            $table->string('addr_addr1', 1024)->nullable();
            $table->string('addr_addr2', 1024)->nullable();
            $table->string('addr_addr3', 1024)->nullable();
            $table->string('addr_addr4', 1024)->nullable();
            $table->string('addr_phone', 128)->nullable();
            $table->string('addr_fax', 128)->nullable();
            $table->string('addr_email', 256)->nullable();
            $table->uuid('terms')->nullable();
            $table->string('tax_inc', 2048)->nullable();
            $table->uuid('tax_table')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
