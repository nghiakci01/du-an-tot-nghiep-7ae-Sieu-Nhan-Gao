<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use App\Http\Requests\Generated\ReviewRequest;

class ReviewRequestTest extends TestCase
{
    public function test_rules_contains_expected_keys()
    {
        $req = new ReviewRequest();
        $rules = $req->rules();

        $this->assertArrayHasKey('rating', $rules);
        $this->assertArrayHasKey('comment', $rules);
        $this->assertStringContainsString('required', $rules['rating']);
        $this->assertStringContainsString('required', $rules['comment']);
    }
}
