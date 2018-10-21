<?php

namespace Tests\Unit;

use App\Site;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_repository_ssh_url()
    {
        $site = factory(Site::class)->make([
            'git_platform' => 'github',
            'repository' => 'user/repository',
        ]);

        $this->assertEquals('git@github.com:user/repository.git', $site->ssh_url);

        $site->git_platform = 'bitbucket';

        $this->assertEquals('git@bitbucket.org:user/repository.git', $site->ssh_url);
    }
}
