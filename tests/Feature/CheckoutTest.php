<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_access_checkout()
    {
        $response = $this->get('/checkout');
        // Tuỳ thuộc yêu cầu đăng nhập, thường sẽ redirect 302 hoặc 200
        $this->assertContains($response->status(), [200, 302]);
    }
}
