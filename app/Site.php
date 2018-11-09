<?php

namespace App;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['name', 'git_platform', 'repository', 'deployment_script'];
    protected $appends = ['ssh_url', 'env_file_contents'];

    /**
     * Return the repository ssh url based on its git_plataform attribute.
     *
     * @return string|null
     */
    public function getSshUrlAttribute()
    {
        switch ($this->git_platform) {
            case 'github':
                return "git@github.com:{$this->repository}.git";
                break;
            case 'bitbucket':
                return "git@bitbucket.org:{$this->repository}.git";
                break;
        }
    }

    /**
     * Returns the env file contents, this is intended to be used only for the
     * toArray() method, in case you want the env file contents please use
     * the method getEnvFileContents() instead.
     *
     * @return string
     */
    public function getEnvFileContentsAttribute()
    {
        return $this->getEnvFileContents();
    }

    /**
     * Returns the path where the site is deployed.
     *
     * @return string
     */
    public function getPath($extra = null)
    {
        $path = config('deploy.path') . "/site_{$this->id}";

        if (is_string($extra)) {
            $path .= '/' . ltrim($extra, '/');
        }

        return $path;
    }

    /**
     * Set the contents of the .env file.
     *
     * @param  string $contents
     * @return void
     */
    public function setEnvFileContents($contents)
    {
        File::put($this->getPath('.env'), $contents);
    }

    /**
     * Get the contents of the .env file.
     *
     * @return string
     */
    public function getEnvFileContents()
    {
        $filePath = $this->getPath('.env');

        try {
            return File::get($this->getPath('.env'));
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return '';
        }
    }
}
