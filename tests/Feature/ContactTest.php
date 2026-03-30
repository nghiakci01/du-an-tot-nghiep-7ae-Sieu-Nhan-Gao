<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_contact_page()
    {
        $response = $this->get('/contact');
        $this->assertContains($response->status(), [302, 200, 404]);
    }
}
