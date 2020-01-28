<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateMealsTable
 */
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
            $table->string('name', 60)->unique();
            $table->text('description')->nullable()->default(null);
            $table->string('image');
            $table->decimal('price', 8, 2);
            $table->unsignedInteger('meal_category_id')->index();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('meal_category_id')
                ->references('id')->on('meal_categories')
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
