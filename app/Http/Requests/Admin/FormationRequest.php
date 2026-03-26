<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FormationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            // ── Champs existants ──
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'category_id'       => ['nullable', 'integer', 'exists:formation_categories,id'],
            'level'             => ['required', 'string', 'in:debutant,intermediaire,avance,tous'],
            'duration'          => ['nullable', 'string', 'max:100'],
            'price'             => ['nullable', 'numeric', 'min:0'],
            'price_promo'       => ['nullable', 'numeric', 'min:0'],
            'content'           => ['nullable', 'string'],
            'is_published'      => ['nullable'],
            'is_featured'       => ['nullable'],
            'has_certification' => ['nullable'],
            'objectives'        => ['nullable', 'array'],
            'objectives.*'      => ['nullable', 'string', 'max:255'],
            'prerequisites'     => ['nullable', 'array'],
            'prerequisites.*'   => ['nullable', 'string', 'max:255'],
            'program'           => ['nullable', 'array'],
            'sort_order'        => ['nullable', 'integer', 'min:0'],
            'max_students'      => ['nullable', 'integer', 'min:1'],
            'thumbnail'         => ['nullable', 'file', 'image', 'max:4096'],
            'banner'            => ['nullable', 'file', 'image', 'max:6144'],

            // ── Nouveaux champs pédagogiques ──
            'participant_profile' => ['nullable', 'string'],
            'accessibility'       => ['nullable', 'string'],
            'access_delay'        => ['nullable', 'string', 'max:200'],
            'teaching_methods'    => ['nullable', 'string'],
            'teaching_resources'  => ['nullable', 'string'],
            'evaluation_methods'  => ['nullable', 'string'],

            // ── Indicateurs chiffrés ──
            'success_rate'     => ['nullable', 'numeric', 'min:0', 'max:100'],
            'satisfaction_rate'=> ['nullable', 'numeric', 'min:0', 'max:100'],
            'employment_rate'  => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'         => 'Le titre de la formation est obligatoire.',
            'title.max'              => 'Le titre ne peut pas dépasser 255 caractères.',
            'level.required'         => 'Le niveau est obligatoire.',
            'level.in'               => 'Le niveau sélectionné est invalide.',
            'price.numeric'          => 'Le prix doit être un nombre.',
            'price.min'              => 'Le prix ne peut pas être négatif.',
            'price_promo.numeric'    => 'Le prix promo doit être un nombre.',
            'price_promo.min'        => 'Le prix promo ne peut pas être négatif.',
            'thumbnail.image'        => 'La miniature doit être une image.',
            'thumbnail.max'          => 'La miniature ne doit pas dépasser 4 Mo.',
            'banner.image'           => 'La bannière doit être une image.',
            'banner.max'             => 'La bannière ne doit pas dépasser 6 Mo.',
            'category_id.exists'     => 'La catégorie sélectionnée est invalide.',
            'success_rate.max'       => 'Le taux de réussite ne peut pas dépasser 100 %.',
            'satisfaction_rate.max'  => 'Le taux de satisfaction ne peut pas dépasser 100 %.',
            'employment_rate.max'    => 'Le taux de retour à l\'emploi ne peut pas dépasser 100 %.',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'is_published'      => filter_var($this->input('is_published'),      FILTER_VALIDATE_BOOLEAN),
            'is_featured'       => filter_var($this->input('is_featured'),        FILTER_VALIDATE_BOOLEAN),
            'has_certification' => filter_var($this->input('has_certification'),  FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}