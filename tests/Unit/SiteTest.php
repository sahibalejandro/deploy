<?php

namespace Tests\Unit;

use App\Site;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        // We use "site_1" as deployment path since each test will start with a
        // clean database.
        exec('mkdir -p "' . config('deploy.path') . '/site_1"');
    }

    public function tearDown()
    {
        exec('rm -rf "' . config('deploy.path') . '"');
        parent::tearDown();
    }

    /** @test */
    public function generates_repository_ssh_url()
    {
        $site = factory(Site::class)->make([
            'git_platform' => 'github',
            'repository' => 'user/repository',
        ]);

        $this->assertEquals('git@github.com:user/repository.git', $site->ssh_url);

        $site->git_platform = 'bitbucket';
        $this->assertEquals('git@bitbucket.org:user/repository.git', $site->ssh_url);
    }

    /** @test */
    public function get_the_site_path()
    {
        $site = factory(Site::class)->create();

        $this->assertEquals( config('deploy.path') . '/site_' . $site->id, $site->getPath() );
    }

    /** @test */
    public function get_the_path_to_a_resource_inside_the_site_path()
    {
        $site = factory(Site::class)->create();
        $sitePath = $site->getPath();

        $this->assertEquals("{$sitePath}/path/to/file", $site->getPath('path/to/file'));
        $this->assertEquals("{$sitePath}/path", $site->getPath('path'));
        $this->assertEquals("{$sitePath}/path", $site->getPath('/path'));
    }

    /** @test */
    public function set_env_file_contents()
    {
        $site = factory(Site::class)->create();
        $contents = 'env contents ' . time();

        $this->assertFileNotExists($site->getPath('.env'));
        $site->setEnvFileContents($contents);

        $this->assertFileExists($site->getPath('.env'));
        $this->assertEquals($contents, File::get($site->getPath('.env')));
    }

    /** @test */
    public function log_a_warning_message_when_it_fails_to_read_the_env_file()
    {
        Log::shouldReceive('warning')->once();
        $site = factory(Site::class)->create();
        $contents = $site->getEnvFileContents();

        $this->assertEquals('', $contents);
    }

    /** @test */
    public function get_env_file_contents()
    {
        $site = factory(Site::class)->create();
        $contents = 'env contents ' . time();
        $site->setEnvFileContents($contents);

        $this->assertEquals($contents, $site->getEnvFileContents());
    }

    /** @test */
    public function appends_env_file_contents_to_the_array_representation()
    {
        $site = factory(Site::class)->create();

        $this->assertArraySubset(['env_file_contents' => ''], $site->toArray());

        $contents = 'env contents ' . time();
        $site->setEnvFileContents($contents);

        $this->assertArraySubset(['env_file_contents' => $contents], $site->toArray());
    }
}
