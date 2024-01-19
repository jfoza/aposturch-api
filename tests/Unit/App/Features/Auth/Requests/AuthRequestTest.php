<?php

namespace Tests\Unit\App\Features\Auth\Requests;

use App\Features\Auth\Requests\AuthRequest;
use Tests\TestCase;

class AuthRequestTest extends TestCase
{
    private array $rules;
    private array $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];

        $this->attributes = [
            'email'    => 'E-mail',
            'password' => 'Password',
        ];
    }

    public function test_should_return_correct_rules()
    {
        $request = new AuthRequest();

        $rules = $request->rules();

        $this->assertEquals($rules, $this->rules);
    }

    public function test_should_return_correct_attributes()
    {
        $request = new AuthRequest();

        $attr = $request->attributes();

        $this->assertEquals($attr, $this->attributes);
    }
}
