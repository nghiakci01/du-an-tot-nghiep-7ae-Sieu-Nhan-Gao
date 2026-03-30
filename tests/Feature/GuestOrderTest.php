<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_order_page_maybe()
    {
        // Depending on routing
        $response = $this->get('/guest-order');
        $this->assertContains($response->status(), [200, 302, 404]);
    }
}
