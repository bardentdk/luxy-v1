<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Étapes du pipeline ─────────────────────────────────────
        Schema::create('deal_stages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->default('#C9A84C');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_won')->default(false);
            $table->boolean('is_lost')->default(false);
            $table->timestamps();
        });

        // ── Contacts CRM ──────────────────────────────────────────
        Schema::create('crm_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('contact_form_id')->nullable()->constrained('contact_forms')->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('source')->default('manual')->comment('manual|contact_form|import');
            $table->string('status')->default('lead')->comment('lead|prospect|client|lost');
            $table->text('notes')->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('last_contacted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('email');
            $table->index('status');
        });

        // ── Deals / Opportunités ──────────────────────────────────
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_contact_id')->constrained('crm_contacts')->cascadeOnDelete();
            $table->foreignId('deal_stage_id')->constrained('deal_stages');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();
            $table->string('title');
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency', 3)->default('EUR');
            $table->date('expected_close_date')->nullable();
            $table->unsignedTinyInteger('probability')->default(50)->comment('0-100%');
            $table->string('lost_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('won_at')->nullable();
            $table->timestamp('lost_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('deal_stage_id');
            $table->index('assigned_to');
        });

        // ── Activités (notes, appels, RDV, emails) ───────────────
        Schema::create('deal_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->cascadeOnDelete();
            $table->foreignId('crm_contact_id')->nullable()->constrained('crm_contacts')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('type')->comment('note|call|email|meeting|task');
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('done_at')->nullable();
            $table->boolean('is_done')->default(false);
            $table->timestamps();

            $table->index(['deal_id', 'type']);
        });

        // ── Produits / Formations vendables ───────────────────────
        Schema::create('commercial_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formation_id')->nullable()->constrained('formations')->nullOnDelete();
            $table->string('name');
            $table->string('reference')->nullable()->unique();
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->string('unit', 50)->default('formation')->comment('formation|heure|jour|personne');
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Devis ─────────────────────────────────────────────────
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_contact_id')->constrained('crm_contacts')->cascadeOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->string('reference')->unique()->comment('DEVIS-2026-0001');
            $table->string('status')->default('draft')->comment('draft|sent|accepted|refused|expired');
            $table->date('issued_at');
            $table->date('expires_at');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->text('terms')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('refused_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('crm_contact_id');
        });

        // ── Lignes de devis ────────────────────────────────────────
        Schema::create('quote_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained('quotes')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('commercial_products')->nullOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 8, 2)->default(1);
            $table->string('unit', 50)->default('formation');
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // ── Séquences email de relance ─────────────────────────────
        Schema::create('email_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users');
            $table->string('name');
            $table->string('trigger')->comment('deal_created|quote_sent|no_activity|stage_changed');
            $table->string('target_stage_slug')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Étapes de la séquence ──────────────────────────────────
        Schema::create('email_sequence_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_sequence_id')->constrained('email_sequences')->cascadeOnDelete();
            $table->unsignedInteger('delay_days')->default(1)->comment('Délai en jours après le trigger');
            $table->string('subject');
            $table->longText('body_html');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── Logs emails envoyés ────────────────────────────────────
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crm_contact_id')->nullable()->constrained('crm_contacts')->nullOnDelete();
            $table->foreignId('deal_id')->nullable()->constrained('deals')->nullOnDelete();
            $table->foreignId('quote_id')->nullable()->constrained('quotes')->nullOnDelete();
            $table->foreignId('sequence_step_id')->nullable()->constrained('email_sequence_steps')->nullOnDelete();
            $table->string('to_email');
            $table->string('to_name')->nullable();
            $table->string('subject');
            $table->longText('body_html')->nullable();
            $table->string('status')->default('sent')->comment('sent|failed|opened|clicked');
            $table->string('brevo_message_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();

            $table->index('crm_contact_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
        Schema::dropIfExists('email_sequence_steps');
        Schema::dropIfExists('email_sequences');
        Schema::dropIfExists('quote_items');
        Schema::dropIfExists('quotes');
        Schema::dropIfExists('commercial_products');
        Schema::dropIfExists('deal_activities');
        Schema::dropIfExists('deals');
        Schema::dropIfExists('crm_contacts');
        Schema::dropIfExists('deal_stages');
    }
};