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
        if (!Schema::hasTable('submissions')) {
            Schema::create('submissions', function (Blueprint $table) {
                $table->id();

                // 🔥 LINK TO USERS TABLE (IMPORTANT)
                $table->string('staff_id', 6);

                $table->string('user_name');

                $table->string('type')->nullable();
                $table->string('category')->nullable();

                $table->text('form_file')->nullable();
                $table->string('form_file_name')->nullable();

                $table->longText('evidence_files')->nullable();
                $table->longText('reviewed_files')->nullable();

                $table->string('status')->default('pending_admin');

                $table->text('admin_comment')->nullable();
                $table->text('head_comment')->nullable();

                $table->timestamp('admin_submitted_at')->nullable();
                $table->timestamp('head_reviewed_at')->nullable();

                $table->timestamps();

                // 🔐 OPTIONAL BUT GOOD (RELATION SAFETY)
                $table->foreign('staff_id')
                    ->references('staff_id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
