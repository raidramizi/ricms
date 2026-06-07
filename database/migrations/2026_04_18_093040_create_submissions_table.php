<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {

            $table->id();

            $table->string('user_name');

            $table->string('type');      // publication / grant
            $table->string('category');  // funding / reward / general etc

            /* =========================
               MAIN FORM FILE
            ========================= */
            $table->string('form_file');          // stored path
            $table->string('form_file_name')->nullable(); // ORIGINAL NAME

            /* =========================
               EVIDENCE FILES (JSON)
            ========================= */
            $table->json('evidence_files')->nullable();

            /* =========================
               STATUS
            ========================= */
            $table->string('status')->default('Pending');

            $table->text('admin_comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
