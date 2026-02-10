<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Seuls les recruteurs peuvent créer des offres
        return auth()->check() && auth()->user()->hasRole('recruteur');
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'type_contrat' => 'required|in:CDI,CDD,Freelance,Stage,Alternance',
            'entreprise' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'description.min' => 'La description doit faire au moins 50 caractères.',
            'type_contrat.in' => 'Type de contrat invalide.',
            'image.image' => 'Le fichier doit être une image.',
            'image.max' => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }
}