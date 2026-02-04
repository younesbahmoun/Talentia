<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    /** Table name (migration created 'friend' not 'friends') */
    // protected $table = 'friend';

    protected $fillable = [
        'user_id',
        'friend_id',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
