<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Symfony\Component\Process\Process;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DatabasesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Instance of User to use authentication.
     *
     * @var \App\User
     */
    protected $user;

    /**
     * This method is called before each test.
     */
    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
        $this->actingAs($this->user);
    }

    /**
     * This method is called after each test.
     */
    public function tearDown()
    {
        $this->deleteTestDatabase();
        parent::tearDown();
    }

    /**
     * Delete the database created during the test.
     *
     * @return void
     */
    protected function deleteTestDatabase()
    {
        $process = new Process(base_path('scripts/delete-test-database'));

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->fail("Failed to delete the test database ({$e->getMessage()}).");
        }
    }

    /**
     * Assert that a connection to the $database can be stablished using the
     * $user and $password credentials.
     *
     * @param  string $database
     * @param  string $user
     * @param  string $password
     * @return $this
     */
    protected function assertDatabaseConnection($database, $user, $password)
    {
        try {
            $pdo = new \PDO("mysql:dbname={$database};host=127.0.0.1", $user, $password);
        } catch (\PDOException $e) {
            $this->fail("Unable to connect to database \"{$database}\". ({$e->getMessage()})");
        }

        return $this;
    }

    /*
     * Tests starts here!
     */

    public function test_all_fields_are_required()
    {
        $this->json('POST', '/api/databases', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'user', 'password']);
    }

    public function test_database_name_field_is_invalid()
    {
        // Test that no spaces are allowed.
        $this->json('POST', '/api/databases', ['name' => ' '])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        // Test that no special chars are allowed.
        $this->json('POST', '/api/databases', ['name' => '.'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);

        // Test that no dashes are allowed.
        $this->json('POST', '/api/databases', ['name' => '-'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_user_field_is_invalid()
    {
        $this->json('POST', '/api/databases', ['user' => ' '])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user']);

        $this->json('POST', '/api/databases', ['user' => '.'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user']);

        $this->json('POST', '/api/databases', ['user' => 'short'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user']);

        $this->json('POST', '/api/databases', ['user' => 'loooooooooooooong'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['user']);
    }

    /** @test */
    public function it_creates_a_new_database()
    {
        $dbName = '_test_database';
        $dbUser = '_test_user';
        $dbPass = 'secretpassword';

        $response = $this->json('POST', '/api/databases', [
                'name' => $dbName,
                'user' => $dbUser,
                'password' => $dbPass,
            ]);

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $this->user->id,
                'name' => $dbName,
                'user' => $dbUser,
            ]);

        $this->assertDatabaseHas('databases', [
            'user_id' => $this->user->id,
            'name' => $dbName,
            'user' => $dbUser,
        ]);

        $this->assertDatabaseConnection($dbName, $dbUser, $dbPass);
    }
}
