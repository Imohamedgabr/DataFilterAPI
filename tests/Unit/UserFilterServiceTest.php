<?php

namespace Tests\Unit;

use App\Http\Services\UserFilterService;
use Tests\TestCase;

class UserFilterServiceTest extends TestCase
{
    private $userFilterService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userFilterService = new UserFilterService();
    }

    /**
     * @test
     */
    public function testFilterByStatusCode()
    {
        $users = [
            (object) [
                'provider' => 'DataProviderX',
                'statusCode' => '1',
            ],
            (object) [
                'provider' => 'DataProviderY',
                'status' => '200',
            ],
        ];
        $statusCode = 'authorised';

        $result = $this->userFilterService->filterUsers($users, function ($user) use ($statusCode) {
            return $this->userFilterService->filterByStatusCode($user, $statusCode);
        });

        $this->assertCount(1, $result);
        $this->assertEquals('DataProviderX', $result[0]->provider);
    }

    /**
     * @test
     */
    public function testFilterByBalance()
    {
        $users = [
            (object) [
                'provider' => 'DataProviderX',
                'parentAmount' => 200,
            ],
            (object) [
                'provider' => 'DataProviderY',
                'balance' => 300,
            ],
        ];
        $balanceMin = 100;
        $balanceMax = 250;

        $result = $this->userFilterService->filterUsers($users, function ($user) use ($balanceMin, $balanceMax) {
            return $this->userFilterService->filterByBalance($user, $balanceMin, $balanceMax);
        });

        $this->assertCount(1, $result);
        $this->assertEquals('DataProviderX', $result[0]->provider);
    }

    /**
     * @test
     */
    public function testFilterByCurrency()
    {
        $users = [
            (object) [
                'provider' => 'DataProviderX',
                'Currency' => 'USD',
            ],
            (object) [
                'provider' => 'DataProviderY',
                'currency' => 'EUR',
            ],
        ];
        $currency = 'USD';

        $result = $this->userFilterService->filterUsers($users, function ($user) use ($currency) {
            return $this->userFilterService->filterByCurrency($user, $currency);
        });

        $this->assertCount(1, $result);
        $this->assertEquals('DataProviderX', $result[0]->provider);
    }
}