<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateUsersTable
 */
class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('first_name', 60);
            $table->string('last_name', 60);
            $table->string('phone', 20)->unique();
            $table->string('address');
            $table->string('email', 60)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'producer_admin', 'producer_user', 'customer_admin', 'customer_user', 'user']);
            $table->unsignedInteger('company_id')->nullable()->default(null)->index();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')->on('companies')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
