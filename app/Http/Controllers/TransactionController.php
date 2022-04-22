<?php

namespace App\Http\Controllers;

use App\Models\Import;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function index(Import $import)
    {
        return view('components.transactions.index');
    }
}
