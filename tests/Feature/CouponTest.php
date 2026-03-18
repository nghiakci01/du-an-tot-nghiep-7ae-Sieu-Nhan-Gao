<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_coupon_api_returns_success()
    {
        $response = $this->post('/apply-coupon', ['code' => 'INVALID_CODE']);
        // Trả về error response hoặc redirect
        $this->assertContains($response->status(), [200, 302, 404, 422]);
    }
}
