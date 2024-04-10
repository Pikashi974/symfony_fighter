<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FightControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('testAdmin@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('POST', '/api/fight', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'user1' => "testAdmin@gmail.com",
            'user2' => "test@gmail.com",
        ]));

        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('winner', $responseData);
    }
}
