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
        Schema::table('tasks', function (Blueprint $table) {
            // Submission type set by admin: 'github', 'file', 'both'
            $table->enum('submission_type', ['github', 'file', 'both'])->default('both')->after('is_late');
            // GitHub link submitted by intern
            $table->string('github_link')->nullable()->after('submission_type');
            // File/folder path submitted by intern
            $table->string('submission_file')->nullable()->after('github_link');
            // Submission notes
            $table->text('submission_notes')->nullable()->after('submission_file');
        });

        // Also add to task_assignments for bulk tasks
        Schema::table('task_assignments', function (Blueprint $table) {
            $table->enum('submission_type', ['github', 'file', 'both'])->default('both')->after('estimated_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['submission_type', 'github_link', 'submission_file', 'submission_notes']);
        });

        Schema::table('task_assignments', function (Blueprint $table) {
            $table->dropColumn('submission_type');
        });
    }
};
