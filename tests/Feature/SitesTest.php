<?php

namespace Tests\Feature;

use App\User;
use App\Site;
use Tests\TestCase;
use App\Jobs\CloneSiteRepository;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SitesTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_validates_that_name_field_is_required()
    {
        $this->json('POST', '/api/sites', ['name' => ''])
            ->assertJsonValidationErrors('name');

        $this->json('POST', '/api/sites', ['name' => 'something'])
            ->assertJsonMissingValidationErrors('name');
    }

    /** @test */
    public function it_validates_that_git_platform_field_is_required()
    {
        $this->json('POST', '/api/sites', ['git_platform' => ''])
            ->assertJsonValidationErrors('git_platform');

        $this->json('POST', '/api/sites', ['git_platform' => 'github'])
            ->assertJsonMissingValidationErrors('git_platform');
    }

    /** @test */
    public function it_validates_that_repository_field_is_required()
    {
        $this->json('POST', '/api/sites', ['repository' => ''])
            ->assertJsonValidationErrors('repository');

        $this->json('POST', '/api/sites', ['repository' => 'user/repository'])
            ->assertJsonMissingValidationErrors('repository');
    }

    /** @test */
    public function it_validates_that_repository_field_is_valid()
    {
        $this->json('POST', '/api/sites', ['repository' => 'bad_repository-name'])
            ->assertJsonValidationErrors('repository');

        $this->json('POST', '/api/sites', ['repository' => 'bad/repository/here'])
            ->assertJsonValidationErrors('repository');

        $this->json('POST', '/api/sites', ['repository' => 'user/repository'])
            ->assertJsonMissingValidationErrors('repository');
    }

    /** @test */
    public function it_validates_that_deployment_script_field_is_required()
    {
        $this->json('POST', '/api/sites', ['deployment_script' => ''])
            ->assertJsonValidationErrors('deployment_script');

        $this->json('POST', '/api/sites', ['deployment_script' => 'npm run production'])
            ->assertJsonMissingValidationErrors('deployment_script');
    }

    /** @test */
    public function it_store_a_new_site()
    {
        Queue::fake();

        $site = factory(Site::class)->make();

        $input = [
            'name' => $site->name,
            'git_platform' => $site->git_platform,
            'repository' => $site->repository,
            'deployment_script' => 'deploy this'
        ];

        $this->json('POST', '/api/sites', $input)->assertStatus(201);

        $this->assertDatabaseHas('sites', [
            'name' => $site->name,
            'git_platform' => $site->git_platform,
            'repository' => $site->repository,
            'installed' => 0,
            'install_error' => null,
            'deployment_script' => 'deploy this',
        ]);

        Queue::assertPushed(CloneSiteRepository::class, function ($job) {
            return $job->site->id === Site::first()->id;
        });
    }
}
