<?php

namespace App\Http\Controllers;


use App\Http\Services\UserFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $filterService;

    public function __construct(UserFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function index(Request $request)
    {
        try {
            $provider = $request->input('provider');
            $statusCode = $request->input('statusCode');
            $balanceMin = $request->input('balanceMin');
            $balanceMax = $request->input('balanceMax');
            $currency = $request->input('currency');

            $validParams = ['provider', 'statusCode', 'balanceMin', 'balanceMax', 'currency'];
            $invalidParams = array_diff(array_keys($request->all()), $validParams);

            if (!empty($invalidParams)) {
                return $this->filterService->createResponse('failure', null, 'Invalid query parameters: ' . implode(', ', $invalidParams), Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $users = [];

            $providers = Config::get('filepaths.providers');

            foreach ($providers as $providerKey => $providerData) {
                if (empty($provider) || $provider === $providerKey) {
                    $dataProviderPath = $providerData['path'];

                    if (!File::exists($dataProviderPath)) {
                        throw new \Exception("File not found: $dataProviderPath");
                    }

                    $fileHandle = fopen($dataProviderPath, 'r');

                    while (!feof($fileHandle)) {
                        $chunk = fread($fileHandle, 8192); // Read 8192 bytes (8KB) at a time

                        // Process the chunk and filter users
                        $dataProviderUsers = json_decode($chunk);
                        $filteredUsers = $this->filterService->filterUsers($dataProviderUsers, function ($user) use ($providerKey, $statusCode, $balanceMin, $balanceMax, $currency) {
                            $user->provider = $providerKey;

                            return (
                                $this->filterService->filterByStatusCode($user, $statusCode) &&
                                $this->filterService->filterByBalance($user, $balanceMin, $balanceMax) &&
                                $this->filterService->filterByCurrency($user, $currency)
                            );
                        });

                        $users = array_merge($users, $filteredUsers);
                    }

                    fclose($fileHandle);
                }
            }

            return $this->filterService->createResponse('success', $users, null, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->filterService->createResponse('failure', null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
