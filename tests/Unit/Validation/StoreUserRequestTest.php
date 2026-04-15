<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreUserRequest;

class StoreUserRequestTest extends TestCase
{
    public function test_valid_user_passes()
    {
        $request = app(StoreUserRequest::class);
        $rules = $request->rules();

        $payload = [
            'name' => 'Nguyen Van A',
            'email' => 'test+'.uniqid().'@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];

        $v = Validator::make($payload, $rules);

        $this->assertFalse($v->fails(), 'Expected valid user payload to pass validation: ' . json_encode($v->errors()->all()));
    }

    public function test_invalid_email_fails()
    {
        $request = app(StoreUserRequest::class);
        $rules = $request->rules();

        $payload = [
            'name' => 'No Email',
            'email' => 'not-an-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ];

        $v = Validator::make($payload, $rules);

        $this->assertTrue($v->fails());
        $this->assertArrayHasKey('email', $v->errors()->messages());
    }
}
