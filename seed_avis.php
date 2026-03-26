<?php

$formations = App\Models\Formation::published()->pluck('id')->toArray();

$avis = [
    ["Marie Fontaine",    "Développeuse junior",  "Cette formation a complètement changé ma carrière. Les formateurs sont excellents et très disponibles.",          5],
    ["Thomas Beaumont",   "Manager",               "Contenu très riche et applicable immédiatement en entreprise. Je recommande vivement !",                          5],
    ["Sarah Grondin",     "Assistante RH",         "La pédagogie est adaptée à tous les niveaux. J'ai progressé rapidement grâce à ce suivi personnalisé.",          5],
    ["Kevin Payet",       "Entrepreneur",          "Formation sérieuse avec de vrais professionnels du secteur réunionnais. Très satisfait.",                         4],
    ["Lucie Bernard",     "Comptable",             "Excellente formation, très bien structurée. Le formateur maîtrise parfaitement son sujet.",                       5],
    ["Mathieu Rivière",   "Technicien IT",         "J'ai obtenu ma certification du premier coup. La préparation était au top.",                                      5],
    ["Nadia Hoarau",      "DRH",                   "Une équipe pédagogique au top, toujours à l'écoute. Je referai une formation ici sans hésiter.",                  4],
    ["Julien Cazal",      "Étudiant",              "Le soutien scolaire m'a vraiment aidé à valider mon BTS. Merci à toute l'équipe !",                               5],
    ["Vanessa Leung",     "Gérante TPE",           "Formation comptabilité très complète. J'ai enfin compris la gestion de mon entreprise.",                          4],
    ["Damien Volard",     "Commercial",            "Super ambiance, formateurs passionnés. Le meilleur investissement de ma carrière.",                               5],
];

$featured = ["Marie Fontaine", "Thomas Beaumont", "Mathieu Rivière"];

foreach ($avis as $a) {
    App\Models\Review::create([
        'reviewer_name' => $a[0],
        'reviewer_job'  => $a[1],
        'comment'       => $a[2],
        'rating'        => $a[3],
        'formation_id'  => !empty($formations) ? $formations[array_rand($formations)] : null,
        'is_approved'   => true,
        'is_featured'   => in_array($a[0], $featured),
        'approved_at'   => now()->subDays(rand(1, 60)),
    ]);
}

echo "OK : " . App\Models\Review::count() . " avis créés.\n";