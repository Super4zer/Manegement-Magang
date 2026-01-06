<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change foreign key constraints from CASCADE to SET NULL
     * so that data (tasks, attendances, etc.) remains when intern is deleted
     */
    public function up(): void
    {
        // Tasks table - make intern_id nullable and set to NULL on delete
        Schema::table('tasks', function (Blueprint $table) {
            // Drop existing foreign key
            $table->dropForeign(['intern_id']);

            // Make column nullable
            $table->foreignId('intern_id')->nullable()->change();

            // Re-add foreign key with SET NULL on delete
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('set null');
        });

        // Attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable()->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('set null');
        });

        // Assessments table
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable()->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('set null');
        });

        // Reports table
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable()->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable(false)->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('cascade');
        });

        // Revert attendances table
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable(false)->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('cascade');
        });

        // Revert assessments table
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable(false)->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('cascade');
        });

        // Revert reports table
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['intern_id']);
            $table->foreignId('intern_id')->nullable(false)->change();
            $table->foreign('intern_id')
                  ->references('id')
                  ->on('interns')
                  ->onDelete('cascade');
        });
    }
};
