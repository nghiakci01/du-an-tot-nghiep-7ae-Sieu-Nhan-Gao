<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_products()
    {
        $response = $this->get('/shop');
        $response->assertStatus(200);
    }
}
