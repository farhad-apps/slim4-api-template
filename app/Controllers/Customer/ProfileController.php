<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\UserService;
use App\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ProfileController extends BaseController
{
    private UserService $userService;
    private int $currentUserId; // از middleware دریافت می‌شود

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->currentUserId = 1; // در واقعیت از token دریافت می‌شود
    }

    public function show(Request $request, Response $response): Response
    {
        try {
            // Customer فقط می‌تونه پروفیل خودش رو ببینه
            $user = $this->userService->getUserById($this->currentUserId);

            return $this->success($response, $user->toArray(), 'پروفیل', 200);
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
            // Customer فقط می‌تونه نام و ایمیل خودش رو تغییر بده
            $data = array_intersect_key($data, array_flip(['name', 'email']));
            $user = $this->userService->updateUser($this->currentUserId, $data);

            return $this->success($response, $user->toArray(), 'پروفیل بروزرسانی شد.', 200);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
