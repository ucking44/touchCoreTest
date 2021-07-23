<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repo extends Model
{
    use HasFactory;

    protected $table = 'repos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'repo_name',
        'url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

}

