<?php

namespace Tests\Unit\App\Features\Auth\Http\Requests;

use App\Features\Auth\Http\Requests\SessionsRequest;
use Tests\TestCase;

class SessionsRequestTest extends TestCase
{
    private array $rules;
    private array $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = [
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|string',
        ];

        $this->attributes = [
            'email'    => 'E-mail',
            'password' => 'Password',
        ];
    }

    public function test_should_return_correct_rules()
    {
        $request = new SessionsRequest();

        $rules = $request->rules();

        $this->assertEquals($rules, $this->rules);
    }

    public function test_should_return_correct_attributes()
    {
        $request = new SessionsRequest();

        $attr = $request->attributes();

        $this->assertEquals($attr, $this->attributes);
    }
}
