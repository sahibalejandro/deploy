<?php

namespace App\Http\Controllers;

use App\Site;

class DashboardController extends Controller
{
    /**
     * Display the dashboard page.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('dashboard')->with(['sites' => Site::all()]);
    }
}
