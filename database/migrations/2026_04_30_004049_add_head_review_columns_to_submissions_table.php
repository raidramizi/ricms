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
    Schema::table('submissions', function (Blueprint $table) {
        $table->text('head_comment')->nullable()->after('status');
        $table->timestamp('head_reviewed_at')->nullable()->after('head_comment');
        $table->string('signed_file')->nullable()->after('head_reviewed_at');
    });
}

public function down(): void
{
    Schema::table('submissions', function (Blueprint $table) {
        $table->dropColumn(['head_comment', 'head_reviewed_at', 'signed_file']);
    });
}
};
