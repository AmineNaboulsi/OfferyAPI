<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Moldes\User;
class Competences extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'level',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'usercompetence', 'competence_id', 'user_id');
    }
}
