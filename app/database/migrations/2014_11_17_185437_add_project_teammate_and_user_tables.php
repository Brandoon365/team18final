<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectTeammateAndUserTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    Schema::create('projects', function($table){
                $table->increments('id');
                $table->string('client');
                $table->string('project');
                $table->integer('min');
                $table->integer('max');
            });
            
            Schema::create('teammates', function($table){
                $table->integer('student');
                $table->integer('teammate');
                $table->boolean('prefer');
                $table->boolean('avoid');
            });
            
            Schema::create('users', function($table) {
                $table->increments('id');
                $table->string('first');
                $table->string('last');
                $table->string('email');
                $table->string('password');
                $table->integer('preference1')->nullable();
                $table->integer('preference2')->nullable();
                $table->integer('preference3')->nullable();
                $table->integer('preference4')->nullable();
                $table->boolean('teamFirst')->nullable();
                $table->boolean('is_admin');
                $table->string('remember_token')->nullable();
            });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	    Schema::drop('teammates');
            Schema::drop('users');
            Schema::drop('projects');
	}

}
