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
        Schema::table('final_grades', function (Blueprint $table) {
            // Add separate grade columns
            $table->decimal('supervisor_grade', 5, 2)->nullable()->after('final_grade');
            $table->json('supervisor_rubric_data')->nullable()->after('supervisor_grade');
            $table->text('supervisor_comments')->nullable()->after('supervisor_rubric_data');
            $table->timestamp('supervisor_graded_at')->nullable()->after('supervisor_comments');
            
            $table->decimal('examiner_grade', 5, 2)->nullable()->after('supervisor_graded_at');
            $table->json('examiner_rubric_data')->nullable()->after('examiner_grade');
            $table->text('examiner_comments')->nullable()->after('examiner_rubric_data');
            $table->timestamp('examiner_graded_at')->nullable()->after('examiner_comments');
            
            // Make existing columns nullable
            $table->decimal('final_grade', 5, 2)->nullable()->change();
            $table->json('rubric_data')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('final_grades', function (Blueprint $table) {
            $table->dropColumn([
                'supervisor_grade',
                'supervisor_rubric_data',
                'supervisor_comments',
                'supervisor_graded_at',
                'examiner_grade',
                'examiner_rubric_data',
                'examiner_comments',
                'examiner_graded_at'
            ]);
        });
    }
};
