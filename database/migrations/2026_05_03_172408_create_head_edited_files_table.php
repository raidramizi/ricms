<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('head_edited_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id'); // FK to submissions table
            $table->string('file_label');                // e.g. "form_file", "evidence_acceptance", "supporting_0"
            $table->string('original_name');             // original uploaded filename
            $table->string('path');                      // storage path
            $table->unsignedBigInteger('uploaded_by');  // head user ID
            $table->timestamps();

            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('uploaded_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('head_edited_files');
    }
};
