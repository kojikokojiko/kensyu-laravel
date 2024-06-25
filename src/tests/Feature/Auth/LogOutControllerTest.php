<?php
declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogOutControllerTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_ログアウトが成功するか()
    {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Act: Send a POST request to the logout route
        $response = $this->post('/logout');

        // Assert: Check the user is logged out
        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'ログアウトに成功しました。');
        $this->assertGuest();
    }
}
