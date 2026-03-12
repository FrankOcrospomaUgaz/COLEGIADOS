<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scientific_associations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('public_registry_certificate')->nullable();
            $table->text('objective')->nullable();
            $table->string('tax_id', 32)->nullable();
            $table->string('legal_address')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'name']);
        });

        Schema::create('scientific_association_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scientific_association_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_name');
            $table->string('responsibility')->nullable();
            $table->integer('display_order')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requester_organization_id')->nullable()->constrained('partner_organizations')->nullOnDelete();
            $table->string('requester_name');
            $table->decimal('credits_awarded', 8, 2)->default(0);
            $table->string('resolution_number')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->enum('status', ['pending', 'approved', 'completed', 'canceled'])->default('approved');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('disciplinary_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reported_name');
            $table->text('reason');
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('resolution')->nullable();
            $table->text('sanction')->nullable();
            $table->enum('status', ['open', 'resolved', 'archived'])->default('open');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('illegal_practice_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('reported_name');
            $table->text('subject');
            $table->text('result')->nullable();
            $table->enum('status', ['reported', 'investigating', 'closed'])->default('reported');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('governing_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('period_name');
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->string('resolution_number')->nullable();
            $table->enum('status', ['planned', 'active', 'closed'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'period_name']);
        });

        Schema::create('governing_period_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('governing_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('member_name');
            $table->string('position')->nullable();
            $table->integer('display_order')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('honorary_distinctions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->text('reason');
            $table->string('resolution')->nullable();
            $table->date('awarded_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('member_deaths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->date('date_of_death');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('fam_benefits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('member_death_id')->nullable()->constrained()->nullOnDelete();
            $table->string('beneficiary_name');
            $table->date('benefit_delivered_at')->nullable();
            $table->string('resolution')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->smallInteger('contribution_years')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('conciliators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->string('registration_number', 64);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'registration_number']);
        });

        Schema::create('retirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->enum('retirement_type', ['cesante', 'jubilada'])->default('jubilada');
            $table->date('cessation_date')->nullable();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->string('former_institution_name')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('partner_organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('legal_representative_name')->nullable();
            $table->string('legal_representative_address')->nullable();
            $table->string('legal_representative_phone', 32)->nullable();
            $table->string('address')->nullable();
            $table->text('benefit')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->integer('duration_months')->nullable();
            $table->date('renewed_at')->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'closed'])->default('active');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agreements');
        Schema::dropIfExists('retirements');
        Schema::dropIfExists('conciliators');
        Schema::dropIfExists('fam_benefits');
        Schema::dropIfExists('member_deaths');
        Schema::dropIfExists('honorary_distinctions');
        Schema::dropIfExists('governing_period_members');
        Schema::dropIfExists('governing_periods');
        Schema::dropIfExists('illegal_practice_reports');
        Schema::dropIfExists('disciplinary_processes');
        Schema::dropIfExists('sponsorships');
        Schema::dropIfExists('scientific_association_members');
        Schema::dropIfExists('scientific_associations');
    }
};
