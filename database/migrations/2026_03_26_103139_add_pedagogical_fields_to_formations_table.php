<?php
// database/migrations/2026_03_26_000001_add_pedagogical_fields_to_formations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            // Après 'content'
            $table->text('participant_profile')->nullable()->after('content')
                  ->comment('Profil des participants');
            $table->text('accessibility')->nullable()->after('participant_profile')
                  ->comment('Accessibilité (handicap, aménagements...)');
            $table->string('access_delay', 200)->nullable()->after('accessibility')
                  ->comment('Délai d\'accès à la formation');
            $table->text('teaching_methods')->nullable()->after('access_delay')
                  ->comment('Modalités pédagogiques');
            $table->text('teaching_resources')->nullable()->after('teaching_methods')
                  ->comment('Moyens et supports pédagogiques');
            $table->text('evaluation_methods')->nullable()->after('teaching_resources')
                  ->comment('Modalités d\'évaluation et de suivi');
            $table->decimal('success_rate', 5, 2)->nullable()->after('evaluation_methods')
                  ->comment('Taux de réussite (%)');
            $table->decimal('satisfaction_rate', 5, 2)->nullable()->after('success_rate')
                  ->comment('Taux de satisfaction (%)');
            $table->decimal('employment_rate', 5, 2)->nullable()->after('satisfaction_rate')
                  ->comment('Taux de retour à l\'emploi après formation (%)');
        });
    }

    public function down(): void
    {
        Schema::table('formations', function (Blueprint $table) {
            $table->dropColumn([
                'participant_profile',
                'accessibility',
                'access_delay',
                'teaching_methods',
                'teaching_resources',
                'evaluation_methods',
                'success_rate',
                'satisfaction_rate',
                'employment_rate',
            ]);
        });
    }
};