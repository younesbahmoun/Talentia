<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'image',
        'entreprise',
        'type_contrat',
        'status',
        'user_id',
    ];
}
