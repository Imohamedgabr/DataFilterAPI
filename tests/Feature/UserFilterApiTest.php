<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserFilterApiTest extends TestCase
{

    /**
     * Test filtering users via API endpoint.
     *
     * @test
     */
    public function testFilterUsersApi()
    {
        $response = $this->get('/api/users?provider=DataProviderY&balanceMin=10&balanceMax=400&currency=AED&statusCode=authorised');

        $expectedResponse = [
            'status' => 'success',
            'users' => [
                [
                    'balance' => 300,
                    'currency' => 'AED',
                    'email' => 'parent1@parent.eu',
                    'status' => 100,
                    'created_at' => '2018-12-22',
                    'id' => '4fc2-a8d1',
                    'provider' => 'DataProviderY',
                ],
                [
                    'balance' => 400,
                    'currency' => 'AED',
                    'email' => 'parent2@parent.eu',
                    'status' => 100,
                    'created_at' => '2019-01-10',
                    'id' => 'a2e6-b9f4',
                    'provider' => 'DataProviderY',
                ],
            ],
            'error' => null,
        ];

        $response->assertStatus(200)
            ->assertJson($expectedResponse);
    }


    /**
     * Test filtering users with invalid query parameters via API endpoint.
     *
     * @test
     */
    public function testFilterUsersApiWithInvalidParameters()
    {
        $response = $this->get('/api/users?provider=DataProviderY&balanceMin=10&balanceMax=400&currency=AED&wrongParam=authorised');

        $expectedResponse = [
            'status' => 'failure',
            'users' => null,
            'error' => 'Invalid query parameters: wrongParam',
        ];

        $response->assertStatus(422)
            ->assertJson($expectedResponse);
    }
}
