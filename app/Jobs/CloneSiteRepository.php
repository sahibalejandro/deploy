<?php

namespace App\Jobs;

use App\Site;
use Illuminate\Bus\Queueable;
use Symfony\Component\Process\Process;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
        // This is the path where the site will be installed.
        $path = config('deploy.path') . "/site_{$this->site->id}";

        // Abort the installation process if the path exists or if it cannot
        // be created, in those cases we mark the installation as failed.
        if (file_exists($path)) {
            $this->failedInstallation("Directory {$path} already exists.");
            return;
        }

        if (!mkdir($path, 0766, true)) {
            $this->failedInstallation("Unable to create directory: {$path}");
            return;
        }

        // Create the process to clone the site's repository into the desired
        // directory, if anything goes wrong with this process we mark the
        // installation as failed and the user will need to reinstall.
        $process = new Process([
            'git',
            'clone',
            $this->site->repository,
            $path,
        ]);

        try {
            $process->mustRun();
            $this->successfulInstallation();
        } catch (ProcessFailedException $e) {
            $this->failedInstallation($process->getErrorOutput());
        }
    }

    /**
     * Sets the installation error message for the current site and marks it
     * as not installed.
     *
     * @return void
     */
    public function failedInstallation($error)
    {
        $this->site->install_error = $error;
        $this->site->installed = false;
        $this->site->save();
    }

    /**
     * Clear the installation error message for the current site and marks it
     * as installed.
     *
     * @return void
     */
    public function successfulInstallation()
    {
        $this->site->install_error = null;
        $this->site->installed = true;
        $this->site->save();
    }
}
