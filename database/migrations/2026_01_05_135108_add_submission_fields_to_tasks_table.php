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
            // Change deadline to datetime for time precision
            $table->time('deadline_time')->nullable()->after('deadline');
            // Submission tracking
            $table->datetime('submitted_at')->nullable()->after('completed_at');
            $table->boolean('is_late')->default(false)->after('submitted_at');
        });

        // Create task_assignments table for assigning tasks to multiple interns
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->date('deadline')->nullable();
            $table->time('deadline_time')->nullable();
            $table->integer('estimated_hours')->nullable();
            $table->boolean('assign_to_all')->default(false);
            $table->timestamps();
        });

        // Pivot table for task assignment to specific interns
        Schema::create('task_assignment_interns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assignment_id')->constrained()->onDelete('cascade');
            $table->foreignId('intern_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Add reference to task_assignment in tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('task_assignment_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['task_assignment_id']);
            $table->dropColumn(['deadline_time', 'submitted_at', 'is_late', 'task_assignment_id']);
        });

        Schema::dropIfExists('task_assignment_interns');
        Schema::dropIfExists('task_assignments');
    }
};
