<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\UserService;
use App\Policies\UserPolicy;
use App\Exceptions\ApiException;
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
            // Admin می‌تونه همه کاربران رو ببینه
            $users = $this->userService->getAllUsers();

            return $this->success($response, $users->toArray(), 'لیست تمام کاربران', 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function getResellerStats(Request $request, Response $response): Response
    {
        try {
            // Query پیچیده: تمام Resellers با تعداد Orders و مجموع فروش
            $stats = $this->userService->getResellerStatistics();

            return $this->success($response, $stats, 'آمار Resellers', 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $user = $this->userService->getUserById($userId);

            // Check authorization
            if (!$this->userPolicy->view(null, $user, 'admin')) {
                throw new ApiException('شما اجازه دسترسی ندارید.', 403);
            }

            return $this->success($response, $user->toArray(), 'جزئیات کاربر', 200);
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
            // Admin می‌تونه هر نقش رو assign کنه
            $data['role'] = $data['role'] ?? 'customer';
            $user = $this->userService->createUser($data);

            return $this->success($response, $user->toArray(), 'کاربر با موفقیت ایجاد شد.', 201);
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

            return $this->success($response, $user->toArray(), 'کاربر بروزرسانی شد.', 200);
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

            return $this->success($response, [], 'کاربر حذف شد.', 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
