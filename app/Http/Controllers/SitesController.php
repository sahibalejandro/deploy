<?php

namespace App\Http\Controllers;

use App\Site;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSite;
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
    public function store(StoreSite $request)
    {
        $input = $request->only(
            'name',
            'git_platform',
            'repository',
            'deployment_script'
        );

        $site = auth()->user()->sites()->save(new Site($input));

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
     * Return the status for the given site.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Site $site)
    {
        return $site->only([
            'installed',
            'install_error',
        ]);
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
