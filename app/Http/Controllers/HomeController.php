<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with([
            'originAccount',
            'destinyAccount',
            'import',
            'user',
        ])->get();
        return view('home', ['transactions' => $transactions]);
    }
}
