<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('submissions', 'admin_comment')) {
                $table->text('admin_comment')->nullable();
            }
            if (!Schema::hasColumn('submissions', 'status_comment')) {
                $table->string('status_comment')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $columns = array_filter(
                ['admin_comment', 'status_comment'],
                fn($col) => Schema::hasColumn('submissions', $col)
            );

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
