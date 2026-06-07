<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('forms', function (Blueprint $table) {

        $table->string('name')->after('id');
        $table->string('file_path')->nullable();
        $table->integer('category_id')->nullable();
        $table->integer('type_id')->nullable();

    });
}

public function down()
{
    Schema::table('forms', function (Blueprint $table) {

        $table->dropColumn(['name', 'file_path', 'category_id', 'type_id']);

    });
}
};
