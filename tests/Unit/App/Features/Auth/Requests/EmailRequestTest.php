<?php

namespace Tests\Unit\App\Features\Auth\Requests;

use App\Features\Auth\Requests\EmailRequest;
use Tests\TestCase;

class EmailRequestTest extends TestCase
{
    private array $rules;
    private array $attributes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rules = [
            'email' => 'required|email:rfc,dns',
        ];

        $this->attributes = [
            'email' => 'E-mail',
        ];
    }

    public function test_should_return_correct_rules()
    {
        $request = new EmailRequest();

        $rules = $request->rules();

        $this->assertEquals($rules, $this->rules);
    }

    public function test_should_return_correct_attributes()
    {
        $request = new EmailRequest();

        $attr = $request->attributes();

        $this->assertEquals($attr, $this->attributes);
    }
}
