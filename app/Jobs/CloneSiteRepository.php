<?php

namespace App\Jobs;

use App\Site;
use Illuminate\Bus\Queueable;
use Symfony\Component\Process\Process;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CloneSiteRepository implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Site to clone the repository from.
     *
     * @var \App\Site
     */
    protected $site;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Site $site)
    {
        $this->site = $site;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO: Validate that this path does not exists before
        // we execute the process to start cloning the repo.
        $path = config('deploy.path') . "/site_{$this->site->id}";

        $process = new Process([
            'git',
            'clone',
            $this->site->repository,
            $path,
        ]);

        $process->run();

        if ($process->isSuccessful()) {
            $this->site->cloned = true;
            $this->site->save();
        } else {
            // TODO: Find a way to notify the user that the repository couldn't
            // be cloned, and we should display a button to try this again.
        }
    }
}
