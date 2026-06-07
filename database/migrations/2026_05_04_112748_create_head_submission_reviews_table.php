<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('head_submission_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('head_id')->constrained('users');

            $table->boolean('all_checked')->default(false);
            $table->text('comment')->nullable();

            $table->enum('status', ['draft', 'recommended', 'rejected'])->default('draft');

            $table->timestamp('submitted_to_admin_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('head_submission_reviews');
    }
};
