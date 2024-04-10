<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ChampionControllerTest extends WebTestCase
{
    public function testCreateChampion(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('testAdmin@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('POST', '/api/admin/champions/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => 'Kikuri',
            'pv' => 10000,
            'power' => 10000
        ]));


        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(201);
        $this->assertJsonStringEqualsJsonString('{"message":"Champion created"}', $client->getResponse()->getContent());
    }
    public function testDuplicateChampion(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('testAdmin@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('POST', '/api/admin/champions/add', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'name' => "Hitori Goto",
            'pv' => 3000,
            'power' => 3000
        ]));


        $response = $client->getResponse();
        $responseData = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(400);
        $this->assertJsonStringEqualsJsonString('{"error":"Champion already exists"}', $client->getResponse()->getContent());
    }
}
