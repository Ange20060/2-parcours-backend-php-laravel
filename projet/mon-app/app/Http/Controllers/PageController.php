<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function accueil()
    {
        return view('acceuil', ['nom' => 'Marie']);
    }
}
