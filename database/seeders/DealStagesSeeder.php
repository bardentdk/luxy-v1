<?php
// database/seeders/DealStagesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DealStagesSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['name' => 'Nouveau lead',     'slug' => 'nouveau-lead',    'color' => '#6366F1', 'sort_order' => 1],
            ['name' => 'Contacté',         'slug' => 'contacte',        'color' => '#F59E0B', 'sort_order' => 2],
            ['name' => 'Devis envoyé',     'slug' => 'devis-envoye',    'color' => '#C9A84C', 'sort_order' => 3],
            ['name' => 'En négociation',   'slug' => 'negociation',     'color' => '#8B5CF6', 'sort_order' => 4],
            ['name' => 'Gagné',            'slug' => 'gagne',           'color' => '#22C55E', 'sort_order' => 5, 'is_won' => true],
            ['name' => 'Perdu',            'slug' => 'perdu',           'color' => '#EF4444', 'sort_order' => 6, 'is_lost' => true],
        ];

        foreach ($stages as $stage) {
            DB::table('deal_stages')->insertOrIgnore(array_merge($stage, [
                'is_won'      => $stage['is_won']  ?? false,
                'is_lost'     => $stage['is_lost'] ?? false,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]));
        }
    }
}