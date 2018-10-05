<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabasesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_new_database()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)
            ->post('/api/databases', [
                'name' => 'my_database',
                'user' => 'my_user',
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'name' => 'my_database',
                'user' => 'my_user',
            ]);

        $this->assertDatabaseHas('databases', [
            'user_id' => $user->id,
            'name' => 'my_database',
            'user' => 'my_user',
        ]);
    }
}
