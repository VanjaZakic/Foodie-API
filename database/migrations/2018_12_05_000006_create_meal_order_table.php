<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMealOrderTable
 */
class CreateMealOrderTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'meal_order';

    /**
     * Run the migrations.
     * @table meal_order
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('meal_id');
            $table->unsignedInteger('user_id');
            $table->decimal('price', 8, 2);
            $table->integer('quantity');

            $table->index(["meal_id"], 'meal_id');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('meal_id')
                ->references('id')->on('meals')
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
