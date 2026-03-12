<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_profile_id')->constrained()->restrictOnDelete();
            $table->foreignId('work_center_id')->nullable()->constrained()->nullOnDelete();
            $table->smallInteger('activity_year')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('research_activity_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('research_activity_id')->constrained()->cascadeOnDelete();
            $table->enum('item_type', ['article', 'research', 'book_or_chapter']);
            $table->text('title');
            $table->date('published_at')->nullable();
            $table->text('url')->nullable();
            $table->integer('display_order')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_activity_items');
        Schema::dropIfExists('research_activities');
    }
};
