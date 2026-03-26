<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GroqService
{
    private const MODEL       = 'llama-3.3-70b-versatile';
    private const MAX_TOKENS  = 600;
    private const TEMPERATURE = 0.7;
    private const TIMEOUT     = 30;

    private const SYSTEM_PROMPT = "Tu es un expert en ingénierie pédagogique et en rédaction de programmes de formation professionnelle pour un organisme de formation à La Réunion (France). Rédige en français, de manière professionnelle, claire et conforme aux exigences Qualiopi. Pas de titre ou sous-titre dans ta réponse, uniquement le contenu demandé.";

    private const FIELD_PROMPTS = [
        'short_description'   => 'Rédige une description courte et percutante (2-3 phrases max, 150 mots max) pour cette formation professionnelle. Sois concis, accrocheur, et orienté bénéfices pour l\'apprenant.',
        'content'             => 'Rédige une description complète et détaillée de cette formation professionnelle en HTML (utilise <p>, <ul>, <li>, <strong>). Développe les points forts, le déroulement général et la valeur ajoutée. 300-400 mots.',
        'participant_profile' => 'Décris le profil idéal des participants pour cette formation (niveau requis, poste occupé, secteur d\'activité, expérience préalable). Sois précis et concret. 3-5 lignes.',
        'accessibility'       => 'Rédige la section accessibilité de cette formation. Mentionne : accessibilité aux personnes en situation de handicap, référent handicap, adaptations possibles des modalités pédagogiques. Sois professionnel et bienveillant. 3-4 lignes.',
        'access_delay'        => 'Indique le délai d\'accès à cette formation. Exemple : "Entrée en formation sous 2 à 4 semaines après validation du dossier d\'inscription". 1 phrase.',
        'teaching_methods'    => 'Décris les modalités pédagogiques : présentiel/distanciel/hybride, méthode active/participative, alternance théorie/pratique, études de cas, mises en situation. 4-6 lignes.',
        'teaching_resources'  => 'Décris les moyens et supports pédagogiques : supports numériques, outils logiciels, matériel, plateforme e-learning, ressources documentaires. 4-6 lignes.',
        'evaluation_methods'  => 'Décris les modalités d\'évaluation et de suivi : évaluations en cours de formation, évaluation finale (QCM, mise en situation, projet...), suivi post-formation, feuilles d\'émargement. 4-6 lignes.',
    ];

    private const LEVEL_LABELS = [
        'debutant'      => 'Débutant',
        'intermediaire' => 'Intermédiaire',
        'avance'        => 'Avancé',
        'tous'          => 'Tous niveaux',
    ];

    // ── Pas de constructeur avec paramètre — la clé est lue à la demande ──

    public function isConfigured(): bool
    {
        return ! empty(config('services.groq.api_key'));
    }

    public function supportedFields(): array
    {
        return array_keys(self::FIELD_PROMPTS);
    }

    /**
     * Génère du contenu IA pour un champ de formation.
     *
     * @throws \RuntimeException
     */
    public function generate(string $field, array $context): string
    {
        $apiKey = config('services.groq.api_key');

        if (empty($apiKey)) {
            throw new \RuntimeException(
                'Clé API Groq non configurée. Ajoutez GROQ_API_KEY dans votre fichier .env'
            );
        }

        $fieldPrompt = self::FIELD_PROMPTS[$field]
            ?? 'Génère un contenu professionnel pour cette formation.';

        $userMessage = $this->buildUserMessage($context, $fieldPrompt);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])
            ->timeout(self::TIMEOUT)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'       => self::MODEL,
                'temperature' => self::TEMPERATURE,
                'max_tokens'  => self::MAX_TOKENS,
                'messages'    => [
                    ['role' => 'system', 'content' => self::SYSTEM_PROMPT],
                    ['role' => 'user',   'content' => $userMessage],
                ],
            ]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \RuntimeException(
                'Impossible de contacter l\'API Groq : ' . $e->getMessage()
            );
        }

        if ($response->failed()) {
            $errorMsg = $response->json('error.message') ?? $response->body();
            throw new \RuntimeException(
                'Erreur API Groq (HTTP ' . $response->status() . ') : ' . $errorMsg
            );
        }

        $content = $response->json('choices.0.message.content', '');

        if (empty(trim($content))) {
            throw new \RuntimeException('Groq a retourné une réponse vide. Réessayez.');
        }

        return trim($content);
    }

    private function buildUserMessage(array $context, string $fieldPrompt): string
    {
        $parts = [];

        if (! empty($context['title']))             $parts[] = 'Formation : "' . $context['title'] . '"';
        if (! empty($context['category']))          $parts[] = 'Domaine : ' . $context['category'];
        if (! empty($context['duration']))          $parts[] = 'Durée : ' . $context['duration'];
        if (! empty($context['level']))             $parts[] = 'Niveau : ' . (self::LEVEL_LABELS[$context['level']] ?? $context['level']);
        if (! empty($context['short_description'])) $parts[] = 'Description courte : ' . $context['short_description'];

        return implode("\n", $parts) . "\n\nTâche : " . $fieldPrompt;
    }
}