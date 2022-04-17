<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public function originAccount()
    {
        return $this->hasOne(Account::class);
    }

    public function destinyAccount()
    {
        return $this->hasOne(Account::class);
    }
}
