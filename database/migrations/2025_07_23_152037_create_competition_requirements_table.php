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
        Schema::create('competition_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // nama field: team_leader_name, member_1_name, etc
            $table->string('field_type'); // text, email, file, select, etc
            $table->string('field_label'); // Label yang ditampilkan
            $table->text('field_options')->nullable(); // JSON untuk options (select, checkbox)
            $table->text('validation_rules')->nullable(); // JSON untuk rules validasi
            $table->boolean('is_required')->default(true);
            $table->integer('order_index')->default(0); // Urutan field
            $table->string('field_group')->default('basic'); // basic, team_member, documents
            $table->text('help_text')->nullable(); // Text bantuan
            $table->timestamps();
            
            $table->index(['competition_id', 'field_group']);
            $table->index(['competition_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_requirements');
    }
};
