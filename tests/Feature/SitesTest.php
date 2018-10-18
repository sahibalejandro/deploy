<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
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
    public function it_validates_that_name_is_required()
    {
        $this->json('POST', '/api/sites', ['name' => ''])
            ->assertJsonValidationErrors('name');

        $this->json('POST', '/api/sites', ['name' => 'something'])
            ->assertJsonMissingValidationErrors('name');
    }

    /** @test */
    public function it_validates_that_repository_is_required()
    {
        $this->json('POST', '/api/sites', ['repository' => ''])
            ->assertJsonValidationErrors('repository');

        $this->json('POST', '/api/sites', ['repository' => 'git@github.com:user/repository.git'])
            ->assertJsonMissingValidationErrors('repository');
    }

    /** @test */
    public function it_validates_that_repository_url_is_valid()
    {
        $this->json('POST', '/api/sites', ['repository' => 'http://bad.repo.com'])
            ->assertJsonValidationErrors('repository');

        $this->json('POST', '/api/sites', ['repository' => 'git@github.com:user/repository.git'])
            ->assertJsonMissingValidationErrors('repository');
    }
}
