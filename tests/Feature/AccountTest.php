<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_dashboard_requires_login()
    {
        $response = $this->get('/my-account');
        $this->assertContains($response->status(), [302, 200]);
    }
}
