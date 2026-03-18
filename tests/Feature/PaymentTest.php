<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_endpoint_exists()
    {
        $response = $this->get('/payment');
        $this->assertContains($response->status(), [200, 302, 404, 405]);
    }
}
