<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_published')->default(false)->index();
            $table->boolean('is_default')->default(false)->index();
            $table->string('variant')->nullable();
            $table->timestamps();
        });

        Schema::create('status_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('status_id')->unsigned()->index();
            $table->string('locale')->index();
            $table->string('name')->index();

            $table->unique(['name','locale']);
            $table->unique(['status_id','locale']);
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('status_translations');
        Schema::dropIfExists('statuses');
    }
}
