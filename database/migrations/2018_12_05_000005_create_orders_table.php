<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateOrdersTable
 */
class CreateOrdersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'orders';

    /**
     * Run the migrations.
     * @table orders
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('price', 8, 2);
            $table->decimal('discount', 3, 2)->nullable()->default('1.00');
            $table->dateTime('delivery_datetime');
            $table->enum('status', ['ordered', 'cancelled', 'processing', 'deliverded']);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('company_id');

            $table->index(["user_id"], 'user_id');

            $table->index(["company_id"], 'company_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('company_id')
                ->references('id')->on('companies')
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
