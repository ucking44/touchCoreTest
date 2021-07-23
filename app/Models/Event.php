<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'event_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repo()
    {
        return $this->hasOne(Repo::class);
    }

}

