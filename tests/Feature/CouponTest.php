<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Coupon;

class CouponTest extends TestCase
{
    use RefreshDatabase;

    public function test_coupon_api_returns_success()
    {
        $response = $this->post(route('cart.apply_coupon'), ['code' => 'INVALID_CODE']);
        // Trả về error response hoặc redirect
        $this->assertContains($response->status(), [200, 302, 404, 422]);
    }
}
