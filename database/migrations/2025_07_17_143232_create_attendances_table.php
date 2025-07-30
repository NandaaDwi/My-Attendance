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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('student_details')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['Present', 'Excused', 'Sick', 'Absent']);
            $table->foreignId('reason_id')->nullable()->constrained('absence_reasons')->onDelete('set null');
            $table->string('note')->nullable();
            $table->foreignId('officer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('academic_year_id')->constrained('academic_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
