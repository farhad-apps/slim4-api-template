<?php

namespace App\Controllers\Reseller;

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
    private int $currentResellerId; // فرض می‌کنیم از middleware دریافت می‌شود

    public function __construct(UserService $userService, UserPolicy $userPolicy)
    {
        $this->userService = $userService;
        $this->userPolicy = $userPolicy;
        $this->currentResellerId = 1; // در واقعیت از token دریافت می‌شود
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            // Reseller فقط مشتریان خودش رو می‌بینه
            $users = $this->userService->getUsersByReseller($this->currentResellerId);

            return $this->success($response, $users->toArray(), 'لیست مشتریان', 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $user = $this->userService->getUserById($userId);

            // Check: آیا این کاربر مال Reseller خودش است؟
            if (!$this->userPolicy->view($this->currentResellerId, $user, 'reseller')) {
                throw new ApiException('شما اجازه دسترسی ندارید.', 403);
            }

            return $this->success($response, $user->toArray(), 'جزئیات مشتری', 200);
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
            // Reseller فقط می‌تونه Customer ایجاد کنه
            $data['role'] = 'customer';
            $data['reseller_id'] = $this->currentResellerId;
            $user = $this->userService->createUser($data);

            return $this->success($response, $user->toArray(), 'مشتری ایجاد شد.', 201);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
