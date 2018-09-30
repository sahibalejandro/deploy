<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * Display the main page.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('main');
    }
}
