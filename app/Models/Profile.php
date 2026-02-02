<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * Les colonnes qui peuvent être remplies en masse
     */
    protected $fillable = [
        'user_id',
        'titre',
        'formation',
        'experiences',
        'competences',
        'photo'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // Use user_id consistently
    }
}