<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_owns_something()
    {
        $user = factory(User::class)->create();
        $something = (object)['user_id' => 0];

        $this->assertFalse($user->owns($something));

        $something->user_id = $user->id;

        $this->assertTrue($user->owns($something));
    }
}
