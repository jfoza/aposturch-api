<?php

namespace Tests\Feature\App\Features\Auth\Controller;

use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\BaseTestCase;
use Tests\Unit\App\Resources\AuthLists;

class AuthControllerTest extends BaseTestCase
{
    private string $loginRoute;
    private string $logoutRoute;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loginRoute = self::LOGIN_ROUTE;
        $this->logoutRoute = self::LOGOUT_ROUTE;
    }

    public function test_should_authenticate_admin_user_by_returning_jwt_token()
    {
        $this->defineAdminUserTypeCredentials();

        $response = $this->postJson(
            $this->loginRoute,
            [
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
            ]
        );

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure(AuthLists::authStructure());
    }

    public function test_should_return_error_if_invalid_email_format()
    {
        $this->defineAdminUserTypeCredentials();

        $response = $this->postJson(
            $this->loginRoute,
            [
                'email' => 'invalid-email',
                'password' => $this->getPassword()
            ]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_return_exception_if_user_not_exists()
    {
        $this->defineAdminUserTypeCredentials();

        $response = $this->postJson(
            $this->loginRoute,
            [
                'email' => 'user-not-exists@email.com',
                'password' => $this->getPassword()
            ]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_should_return_exception_if_passwords_not_match()
    {
        $this->defineAdminUserTypeCredentials();

        $response = $this->postJson(
            $this->loginRoute,
            [
                'email' => $this->getEmail(),
                'password' => 'pass'
            ]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_should_return_exception_if_user_is_inactive()
    {
        $this->defineAdminUserTypeInactiveCredentials();

        $response = $this->postJson(
            $this->loginRoute,
            [
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
            ]
        );

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_should_perform_logout_action()
    {
        $this->defineAdminUserTypeCredentials();

        $loginResponse = $this->postJson(
            $this->loginRoute,
            [
                'email' => $this->getEmail(),
                'password' => $this->getPassword()
            ]
        );

        $content = $loginResponse->getOriginalContent();

        $response = $this->getJson(
            $this->logoutRoute,
            ['Authorization' => "Bearer {$content->accessToken}"]
        );

        $response->assertNoContent();
    }

    public function test_should_return_unauthorized_in_logout_if_token_is_invalid()
    {
        $response = $this->getJson(
            $this->logoutRoute,
            $this->getInvalidAuthorizationBearer()
        );

        $response->assertUnauthorized();
    }
}
