<?php
// seed_session_test.php à la racine
$formation = App\Models\Formation::published()->first();
if (!$formation) { echo "Aucune formation publiée.\n"; exit; }

App\Models\FormationSession::create([
    'formation_id'  => $formation->id,
    'label'         => 'Session Avril 2026',
    'start_date'    => '2026-04-14',
    'end_date'      => '2026-04-25',
    'schedule'      => 'Lundi / Mercredi 18h–20h',
    'location'      => 'Saint-Denis, La Réunion',
    'modality'      => 'presentiel',
    'seats_total'   => 12,
    'seats_taken'   => 4,
    'is_open'       => true,
    'is_published'  => true,
]);

echo "Session créée pour : " . $formation->title . "\n";