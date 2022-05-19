<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuspectTransactionsController extends Controller
{
    public function index(Request $request)
    {
        return view('components.transactions.suspect.index', [
            'transactions' => collect(),
        ]);
    }

    public function suspectTransactions(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);
    }
}
