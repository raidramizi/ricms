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
    Schema::table('file_edits', function (Blueprint $table) {
        $table->string('file_type')->after('submission_id');
        // main | evidence | optional
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
