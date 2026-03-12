<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('first_names');
            $table->string('last_names');
            $table->date('date_of_birth');
            $table->enum('sex', ['female', 'male', 'non_binary', 'prefer_not_to_say']);
            $table->string('cellphone', 32)->nullable();
            $table->string('email')->nullable();
            $table->string('college_number', 32)->nullable();
            $table->foreignId('base_university_id')->nullable()->constrained('academic_institutions')->nullOnDelete();
            $table->text('licensure_research_title')->nullable();
            $table->text('licensure_thesis_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'college_number']);
            $table->index(['institution_id', 'last_names']);
        });

        Schema::create('master_degree_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->string('record_number', 64);
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sunedu_code', 64)->nullable();
            $table->smallInteger('graduation_year')->nullable();
            $table->text('research_title')->nullable();
            $table->text('thesis_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'record_number']);
            $table->unique(['institution_id', 'sunedu_code']);
        });

        Schema::create('doctorate_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->string('record_number', 64);
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('academic_institution_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sunedu_code', 64)->nullable();
            $table->smallInteger('graduation_year')->nullable();
            $table->text('research_title')->nullable();
            $table->text('thesis_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'record_number']);
            $table->unique(['institution_id', 'sunedu_code']);
        });

        Schema::create('second_specialty_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('specialty_name');
            $table->string('sunedu_code', 64)->nullable();
            $table->foreignId('academic_institution_id')->nullable()->constrained()->nullOnDelete();
            $table->text('research_title')->nullable();
            $table->text('thesis_url')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'sunedu_code']);
        });

        Schema::create('auditor_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('diploma_granter_id')->nullable()->constrained('partner_organizations')->nullOnDelete();
            $table->string('record_number', 64);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'record_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditor_records');
        Schema::dropIfExists('second_specialty_records');
        Schema::dropIfExists('doctorate_records');
        Schema::dropIfExists('master_degree_records');
        Schema::dropIfExists('member_profiles');
    }
};
