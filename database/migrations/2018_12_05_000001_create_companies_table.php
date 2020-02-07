<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateCompaniesTable
 */
class CreateCompaniesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'companies';

    /**
     * Run the migrations.
     * @table companies
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 60);
            $table->string('phone', 20)->unique();
            $table->string('address');
            $table->string('email', 60)->unique();
            $table->enum('type', ['producer', 'customer']);
            $table->string('image');
            $table->decimal('lat', 10, 8)->nullable()->default(null);
            $table->decimal('lng', 11, 8)->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists($this->tableName);
    }
}
