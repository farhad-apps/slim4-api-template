<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Exceptions\ApiException;
use App\Helpers\Translator;
use App\Policies\UserPolicy;
use App\Services\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{
    private UserService $userService;
    private UserPolicy $userPolicy;

    public function __construct(UserService $userService, UserPolicy $userPolicy)
    {
        $this->userService = $userService;
        $this->userPolicy = $userPolicy;
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $users = $this->userService->getAllUsers();

            return $this->success($response, $users->toArray(), Translator::trans('user.list'), 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function getResellerStats(Request $request, Response $response): Response
    {
        try {
            $stats = $this->userService->getResellerStatistics();

            return $this->success($response, $stats, 'Reseller statistics', 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $user = $this->userService->getUserById($userId);

            if (!$this->userPolicy->view(null, $user, 'admin')) {
                throw new ApiException(Translator::trans('auth.access_denied'), 403);
            }

            return $this->success($response, $user->toArray(), Translator::trans('user.profile'), 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function store(Request $request, Response $response): Response
    {
        try {
            $data = $request->getParsedBody();
            $data['role'] = $data['role'] ?? 'customer';
            $user = $this->userService->createUser($data);

            return $this->success($response, $user->toArray(), Translator::trans('user.created'), 201);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $data = $request->getParsedBody();
            $user = $this->userService->updateUser($userId, $data);

            return $this->success($response, $user->toArray(), Translator::trans('user.updated'), 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $this->userService->deleteUser($userId);

            return $this->success($response, [], Translator::trans('user.deleted'), 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
