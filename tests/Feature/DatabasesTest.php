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
        $dbName = '_test_database';
        $dbUser = '_test_user';
        $dbPassword = 'secret';

        $response = $this->actingAs($user)
            ->post('/api/databases', [
                'name' => $dbName,
                'user' => $dbUser,
                'password' => $dbPassword,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'name' => $dbName,
                'user' => $dbUser,
            ]);

        $this->assertDatabaseHas('databases', [
            'user_id' => $user->id,
            'name' => $dbName,
            'user' => $dbUser,
        ]);

        $this->assertDatabaseConnection($dbName, $dbUser, $dbPassword);
    }

    protected function assertDatabaseConnection($database, $user, $password)
    {
        try {
            $pdo = new \PDO("mysql:dbname={$database};host=127.0.0.1", $user, $password);
        } catch (\PDOException $e) {
            $this->fail("Unable to connect to database \"{$database}\". ({$e->getMessage()})");
        }

        return $this;
    }
}
