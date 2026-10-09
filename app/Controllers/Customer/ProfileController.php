<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Exceptions\ApiException;
use App\Helpers\Translator;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends BaseController
{
    private UserService $userService;
    private int $currentUserId;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->currentUserId = 1;
    }

    public function show(Request $request, Response $response): Response
    {
        try {
            $user = $this->userService->getUserById($this->currentUserId);

            return $this->success($response, $user->toArray(), Translator::trans('user.profile'), 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function update(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $data = array_intersect_key($data, array_flip(['name', 'email']));
            $user = $this->userService->updateUser($this->currentUserId, $data);

            return $this->success($response, $user->toArray(), Translator::trans('user.updated'), 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
