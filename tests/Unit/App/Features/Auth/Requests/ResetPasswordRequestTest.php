<?php

namespace Tests\Unit\App\Features\Auth\Requests;

use App\Features\Auth\Requests\ResetPasswordRequest;
use Tests\TestCase;

class ResetPasswordRequestTest extends TestCase
{
    private array $rules;
    private array $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = [
            'password' => 'required|string',
            'passwordConfirmation' => 'required|same:password'
        ];

        $this->attributes = [
            'password' => 'Senha',
            'passwordConfirmation' => 'Confirmação de senha',
        ];
    }

    public function test_should_return_correct_rules()
    {
        $request = new ResetPasswordRequest();

        $rules = $request->rules();

        $this->assertEquals($rules, $this->rules);
    }

    public function test_should_return_correct_attributes()
    {
        $request = new ResetPasswordRequest();

        $attr = $request->attributes();

        $this->assertEquals($attr, $this->attributes);
    }
}
