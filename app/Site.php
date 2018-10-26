<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = ['name', 'git_platform', 'repository', 'deployment_script'];
    protected $appends = ['ssh_url'];

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
}
