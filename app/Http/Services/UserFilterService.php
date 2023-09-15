<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Http\JsonResponse;

class UserFilterService
{
    public function filterUsers(array $users, callable $filterCallback) : array
    {
        return array_values(array_filter($users, $filterCallback));
    }

    public function filterByStatusCode(object $user, ?string $statusCode): bool
    {
        if ($statusCode === null) {
            return true;
        }

        if ($user->provider === 'DataProviderX') {
            $statusMapping = Config::get('filepaths.providers.' . $user->provider .'.status_codes');
            $userStatus = $statusMapping[$user->statusCode] ?? null;

            return $userStatus === $statusCode;
        } elseif ($user->provider === 'DataProviderY') {
            $statusMapping = Config::get('filepaths.providers.' . $user->provider .'.status_codes');
            $userStatus = $statusMapping[$user->status] ?? null;

            return $userStatus === $statusCode;
        }

        return false;
    }

    public function filterByBalance(object $user, ?float $balanceMin, ?float $balanceMax): bool
    {
        if ($balanceMin === null && $balanceMax === null) {
            return true;
        }
        if ($user->provider === 'DataProviderX' && property_exists($user, Config::get('filepaths.providers.' . $user->provider .'.amount') )) {
            if ($balanceMin !== null && $user->parentAmount < $balanceMin) {
                return false;
            }

            if ($balanceMax !== null && $user->parentAmount > $balanceMax) {
                return false;
            }
        }

        if ($user->provider === 'DataProviderY' && property_exists($user, Config::get('filepaths.providers.' . $user->provider .'.amount') )) {
            if ($balanceMin !== null && $user->balance < $balanceMin) {
                return false;
            }

            if ($balanceMax !== null && $user->balance > $balanceMax) {
                return false;
            }
        }

        return true;
    }

    public function filterByCurrency(object $user, ?string $currency): bool
    {
        if ($currency === null) {
            return true;
        }
    
        if ($user->provider === 'DataProviderX' && property_exists($user, Config::get('filepaths.providers.' . $user->provider .'.currency') )) {
            return $user->Currency === $currency;
        }
    
        if ($user->provider === 'DataProviderY' && property_exists($user, Config::get('filepaths.providers.' . $user->provider .'.currency') )) {
            return $user->currency === $currency;
        }
    
        return false;
    }

    public function createResponse(string $status, ?array $users = null, ?string $error = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'users' => $users,
            'error' => $error,
        ], $statusCode);
    }
}