<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuspectTransactionsController extends Controller
{
    public function index(Request $request)
    {
        return view('components.transactions.suspect.index');
    }
}
