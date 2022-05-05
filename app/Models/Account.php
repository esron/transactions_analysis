<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch',
        'number',
    ];

    public function transactions()
    {
        return $this->hasMany(Account::class);
    }
}
