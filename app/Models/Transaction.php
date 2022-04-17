<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Import;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_account_id',
        'destiny_account_id',
        'import_id',
        'amount',
    ];

    public function originAccount()
    {
        return $this->hasOne(Account::class);
    }

    public function destinyAccount()
    {
        return $this->hasOne(Account::class);
    }

    public function import()
    {
        return $this->hasOne(Import::class);
    }
}
