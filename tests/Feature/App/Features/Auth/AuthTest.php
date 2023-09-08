<?php

namespace Tests\Feature\App\Features\Auth;

use App\Shared\Libraries\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tests\Unit\App\Resources\AuthLists;

class AuthTest extends BaseAuthTestCase
{
    private string $loginRoute;
    private string $logoutRoute;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loginRoute = self::LOGIN_ROUTE;
        $this->logoutRoute = self::LOGOUT_ROUTE;
    }

    public function dataProviderCredentials(): array
    {
        return [
            'Admin Master' => [Credentials::ADMIN_MASTER, Credentials::PASSWORD],
            'Admin Church' => [Credentials::ADMIN_CHURCH_1, Credentials::PASSWORD],
            'Admin Module' => [Credentials::ADMIN_MODULE, Credentials::PASSWORD],
            'Assistant'    => [Credentials::ASSISTANT_1, Credentials::PASSWORD],
        ];
    }

    /**
     * @dataProvider dataProviderCredentials
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    public function test_should_authenticate_admin_user_by_returning_jwt_token(
        string $email,
        string $password,
    ): void
    {
        $this->setEmail($email);
        $this->setPassword($password);

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
        $this->setPassword(Credentials::PASSWORD);

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
        $this->setPassword(Credentials::PASSWORD);

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
        $this->setEmail(Credentials::ADMIN_MASTER);

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
        $this->setEmail(Credentials::INACTIVE_USER);
        $this->setPassword(Credentials::PASSWORD);

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
        $this->setEmail(Credentials::ADMIN_MASTER);
        $this->setPassword(Credentials::PASSWORD);

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
        $token = hash('sha256', Uuid::uuid4Generate());

        $response = $this->getJson(
            $this->logoutRoute,
            ['Authorization' => "Bearer {$token}"]
        );

        $response->assertUnauthorized();
    }
}
