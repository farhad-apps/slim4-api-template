<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $users = $this->userService->getAllUsers();

            return $this->success($response, $users->toArray(), 'لیست کاربران', 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $user = $this->userService->getUserById($userId);

            return $this->success($response, $user->toArray(), 'کاربر', 200);
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
            $user = $this->userService->createUser($data);

            return $this->success($response, $user->toArray(), 'کاربر با موفقیت ایجاد شد.', 201);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), $e->getErrors(), $e->getStatus());
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

            return $this->success($response, $user->toArray(), 'کاربر با موفقیت بروزرسانی شد.', 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), $e->getErrors(), $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $this->userService->deleteUser($userId);

            return $this->success($response, [], 'کاربر با موفقیت حذف شد.', 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
