<?php

namespace Tests\Feature;

use PDO;
use App\User;
use App\Database;
use PDOException;
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

    /** @test */
    public function it_checks_that_all_fields_are_required()
    {
        $this->json('POST', '/api/databases', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'user', 'password']);
    }

    /** @test */
    public function it_checks_if_the_database_name_field_is_invalid()
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

    /** @test */
    public function it_checks_if_the_user_field_is_invalid()
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
    public function it_checks_the_password_must_be_confirmed()
    {
        $this->json('POST', '/api/databases', ['password' => 'secret'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        $this->json('POST', '/api/databases', ['password' => 'secret', 'password_confirmation' => 'secret_wrong'])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        $this->json('POST', '/api/databases', ['password' => 'secret', 'password_confirmation' => 'secret'])
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors(['password']);
    }

    /** @test */
    public function do_not_create_new_database_if_it_already_exists()
    {
        $database = $this->createTestDatabase();
        $input = [
            'name' => $database->name,
            'user' => 'another_user',
            'password' => 'secretpassword',
        ];

        $this->json('POST', '/api/databases', $input)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_creates_a_new_database()
    {
        $dbName = '_test_database';
        $dbUser = '_test_user';
        $dbPass = 'secretpassword';
        $input = ['name' => $dbName, 'user' => $dbUser, 'password' => $dbPass, 'password_confirmation' => $dbPass];

        $this->json('POST', '/api/databases', $input)
            ->assertStatus(201)
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

    /** @test */
    public function the_user_cannot_delete_a_database_that_belongs_to_another_user()
    {
        $databasePassword = 'secretpassword';
        $database = $this->createTestDatabase($databasePassword);
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('DELETE', "/api/databases/{$database->id}")
            ->assertStatus(403);

        $this->assertDatabaseConnection(
            $database->name,
            $database->user,
            $databasePassword
        );
    }

    /** @test */
    public function it_deletes_an_existing_database()
    {
        $database = $this->createTestDatabase();

        $this->json('DELETE', "/api/databases/{$database->id}")
            ->assertStatus(200);

        $this->assertDatabaseAndUserDoesNotExists($database->name, $database->user);
    }

    /**
     * Delete the database created during the test.
     *
     * @return void
     */
    protected function deleteTestDatabase()
    {
        $process = new Process([
            base_path('scripts/delete-database'),
            '_test_database',
            '_test_user',
        ]);

        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            $this->fail("Failed to delete the test database ({$e->getMessage()}).");
        }
    }

    /**
     * Creates a test database.
     *
     * @param  string $password
     * @return \App\Database
     */
    protected function createTestDatabase($password = 'secretpassword')
    {
        $database = factory(Database::class)->create([
            'user_id' => $this->user->id,
        ]);

        $process = new Process([
            base_path('scripts/create-database'),
            $database->name,
            $database->user,
            $password,
        ]);

        $process->mustRun();

        return $database;
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
            $pdo = new PDO("mysql:dbname={$database};host=127.0.0.1", $user, $password);
        } catch (PDOException $e) {
            $this->fail("Unable to connect to database \"{$database}\". ({$e->getMessage()})");
        }

        return $this;
    }

    /**
     * Assert that the given database and user does not exists.
     *
     * @param  string $database
     * @param  string $user
     * @return $this
     */
    protected function assertDatabaseAndUserDoesNotExists($database, $user)
    {
        $db = config('database.connections.mysql');
        $pdo = new PDO("mysql:host={$db['host']}", $db['username'], $db['password']);

        // First check that the database does not exists.
        $rows = $pdo->query('SHOW DATABASES;')->fetchAll(PDO::FETCH_OBJ);
        foreach ($rows as $row) {
            if ($row->Database === $database) {
                $this->fail("Database \"{$database}\" should not exists.");
            }
        }

        // Check that the user does not exists.
        $query = $pdo->prepare('SELECT COUNT(*) AS `count` FROM mysql.user WHERE User=?');
        $query->execute([$user]);
        $rows = $query->fetchAll(PDO::FETCH_OBJ);
        if (intval($rows[0]->count) !== 0) {
            $this->fail("User \"{$user}\" should not exists.");
        }

        return $this;
    }
}
