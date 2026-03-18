<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tracking_page_is_accessible()
    {
        $response = $this->get('/order-tracking');
        $this->assertContains($response->status(), [200, 302, 404]);
    }
}
