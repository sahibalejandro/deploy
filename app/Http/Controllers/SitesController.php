<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;

class SitesController extends Controller
{
    /**
     * Display the form to create a new site.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('sites.create');
    }

    /**
     * Create a new site assigned to the current authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $site = auth()->user()->sites()->save(
            new Site($request->only('name'))
        );

        alert("The new site was created.");
        return redirect('/');
    }

    /**
     * Display the detail page for the given site.
     *
     * @param  \App\Site $site
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Site $site)
    {
        return view('sites.show')->with(compact('site'));
    }

    /**
     * Delete the given site.
     *
     * @param  \App\Site $site
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Site $site)
    {
        $site->delete();

        alert('Site is deleted.');
        return redirect('/');
    }
}
