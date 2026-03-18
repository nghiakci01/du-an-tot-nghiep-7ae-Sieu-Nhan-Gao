<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_cart()
    {
        $response = $this->get('/cart');
        $response->assertStatus(200);
    }
}
