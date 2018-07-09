<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('position')->nullable();
            $table->integer('menu_type')->default(1);
            $table->string('icon')->nullable();
            $table->string('singular_name')->unique();
            $table->string('plural_name')->unique();
            $table->string('title');
            $table->integer('parent_id')->nullable();
            $table->string('roles')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
