<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('legal_name')->nullable();
            $table->string('tax_id', 32)->nullable();
            $table->string('institution_type')->default('professional_association');
            $table->string('email')->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('website')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Peru');
            $table->string('primary_color', 16)->default('#0f766e');
            $table->string('secondary_color', 16)->default('#b45309');
            $table->enum('status', ['trial', 'active', 'suspended'])->default('trial');
            $table->jsonb('settings')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('monthly_price', 12, 2)->default(0);
            $table->decimal('annual_price', 12, 2)->default(0);
            $table->integer('max_users')->nullable();
            $table->integer('max_records')->nullable();
            $table->jsonb('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('institution_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained()->restrictOnDelete();
            $table->enum('status', ['trial', 'active', 'past_due', 'canceled'])->default('trial');
            $table->enum('billing_cycle', ['monthly', 'annual', 'manual'])->default('manual');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->integer('max_users_override')->nullable();
            $table->integer('max_records_override')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('name');
            $table->foreignId('current_institution_id')->nullable()->after('about')->constrained('institutions')->nullOnDelete();
            $table->timestamp('last_seen_at')->nullable()->after('remember_token');
            $table->softDeletes();
        });

        Schema::create('institution_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('role', ['owner', 'admin', 'editor', 'viewer'])->default('viewer');
            $table->enum('status', ['invited', 'active', 'suspended'])->default('active');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('last_accessed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['institution_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institution_memberships');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('current_institution_id');
            $table->dropColumn(['job_title', 'last_seen_at', 'deleted_at']);
        });

        Schema::dropIfExists('institution_subscriptions');
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('institutions');
    }
};
