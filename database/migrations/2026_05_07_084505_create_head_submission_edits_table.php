<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('head_submission_edits', function (Blueprint $table) {

    $table->id();

    $table->foreignId('submission_id')->constrained()->onDelete('cascade');

    $table->foreignId('head_id')->constrained('users')->onDelete('cascade');

    $table->string('file_type');

    $table->string('file_key')->nullable();

    $table->string('old_file')->nullable();

    $table->string('new_file');

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('head_submission_edits');
    }
};
