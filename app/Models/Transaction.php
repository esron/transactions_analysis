<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Import;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

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
        return $this->belongsTo(Account::class);
    }

    public function destinyAccount()
    {
        return $this->belongsTo(Account::class);
    }

    public function import()
    {
        return $this->belongsTo(Import::class);
    }

    public function formattedAmount(): Attribute
    {
        $currencies = new ISOCurrencies();
        $numberFormatter = new \NumberFormatter('pt_BR', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);
        return Attribute::make(
            get: function ($value, $attributes) use ($moneyFormatter) {
                return $moneyFormatter->format(new Money($attributes['amount'], new Currency('BRL')));
            }
        );
    }
}
