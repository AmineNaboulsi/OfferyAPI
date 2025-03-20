<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefreshToken
{
    public $token;

    public function __construct(string $token = null)
    {
        $this->token = $token;
    }
}
