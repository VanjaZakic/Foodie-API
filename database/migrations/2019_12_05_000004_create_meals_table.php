<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'meals';

    /**
     * Run the migrations.
     * @table meals
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 60);
            $table->text('description')->nullable()->default(null);
            $table->string('image');
            $table->decimal('price', 8, 2);
            $table->unsignedInteger('meal_category_id');
            $table->unsignedInteger('user_id');
            $table->enum('user_role', ['admin', 'producer_admin', 'producer_user', 'customer_admin', 'customer_user', 'user']);

            $table->index(["user_id", "user_role"], 'user_id');

            $table->index(["meal_category_id"], 'meal_category_id');

            $table->unique(["image"], 'image');

            $table->unique(["name"], 'name');
            $table->softDeletes();
            $table->timestamps();


            $table->foreign('meal_category_id')
                ->references('id')->on('meal_categories')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
