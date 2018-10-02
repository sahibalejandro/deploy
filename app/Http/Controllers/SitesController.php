<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use App\Jobs\CloneSiteRepository;

class SitesController extends Controller
{
    /**
     * Return the list for the current authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return auth()->user()->sites;
    }

    /**
     * Create a new site assigned to the current authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $site = auth()->user()->sites()->save(
            new Site($request->only('name', 'repository'))
        );

        // Dispatch the job to clone the repository, this job
        // is queued and handled by the super queue worker.
        CloneSiteRepository::dispatch($site);

        return $site;
    }

    /**
     * Display the detail page for the given site.
     *
     * @param  \App\Site $site
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Site $site)
    {
        return $site;
    }

    /**
     * Delete the given site.
     *
     * @param  \App\Site $site
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Site $site)
    {
        // TODO: Delete the entire site's directory.
        $site->delete();

        alert('Site is deleted.');
        return redirect('/');
    }
}
