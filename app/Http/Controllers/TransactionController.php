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
        $import->load([
            'transactions.originAccount',
            'transactions.destinyAccount',
            'user'
        ]);
        return view('components.transactions.index', ['import' => $import]);
    }
}
