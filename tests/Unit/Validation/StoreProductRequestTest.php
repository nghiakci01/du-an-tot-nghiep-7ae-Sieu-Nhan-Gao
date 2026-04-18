<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreProductRequest;

class StoreProductRequestTest extends TestCase
{
    public function test_valid_payload_passes()
    {
        $request = app(StoreProductRequest::class);
        $rules = $request->rules();

        $payload = [
            'name' => 'Test product',
            'category_id' => 1,
            'price' => 1000,
            'variants' => [
                [
                    'size_id' => 1,
                    'color_id' => 1,
                    'stock_quantity' => 10,
                ],
            ],
        ];

        $v = Validator::make($payload, $rules);

        $this->assertFalse($v->fails(), 'Expected valid payload to pass validation: ' . json_encode($v->errors()->all()));
    }

    public function test_missing_required_fields_fails()
    {
        $request = app(StoreProductRequest::class);
        $rules = $request->rules();

        $payload = [
            // missing name, category_id, variants
        ];

        $v = Validator::make($payload, $rules);

        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('name', $v->errors()->messages());
        $this->assertArrayHasKey('category_id', $v->errors()->messages());
        $this->assertArrayHasKey('variants', $v->errors()->messages());
    }
}
