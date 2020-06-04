<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('author_id')->unsigned()->index();
            $table->bigInteger('status_id')->unsigned()->index();
            $table->timestamps();

            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('restrict');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('page_id')->unsigned()->index();
            $table->string('locale')->index();
            $table->string('slug')->index();
            $table->string('title')->nullable()->index();
            $table->string('headline')->nullable()->index();
            $table->text('body')->nullable();
            $table->string('meta_title')->nullable()->index();
            $table->string('meta_description')->nullable()->index();
            $table->string('meta_keywords')->nullable()->index();

            $table->unique(['slug','locale']);
            $table->unique(['page_id','locale']);
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_translations');
        Schema::dropIfExists('pages');
    }
}
