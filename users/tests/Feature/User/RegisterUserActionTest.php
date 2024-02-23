<?php

namespace App\Tests\Feature\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegisterUserActionTest extends WebTestCase
{
    public function testRegisterUser(): void
    {
        $client = static::createClient();

        $requestData = [
            'email'       => 'test@example.com',
            'password'    => 'testpassword',
            'firstname'   => 'Test',
            'lastname'    => 'User',
            'nationality' => 'TestNationality'
        ];

        $client->request(
            'POST',
            '/api/v1/auth/user',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($requestData)
        );

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }
}
