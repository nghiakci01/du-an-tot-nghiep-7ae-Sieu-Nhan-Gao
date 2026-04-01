<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSafetyTest extends TestCase
{
    use RefreshDatabase;

    public function test_dev_payment_routes_are_hidden_by_default(): void
    {
        $this->get('/test-payment/create-order')->assertNotFound();
    }

    public function test_stock_report_route_is_hidden_by_default(): void
    {
        $this->get('/admin/stock')->assertNotFound();
    }
}
