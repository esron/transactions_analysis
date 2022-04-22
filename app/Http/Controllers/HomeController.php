<?php

namespace App\Http\Controllers;

use App\Models\Import;

class HomeController extends Controller
{
    public function index()
    {
        $imports = Import::with(['user'])->get();
        return view('home', ['imports' => $imports]);
    }
}
