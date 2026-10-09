<?php

namespace App\Controllers\Reseller;

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
    private int $currentResellerId;

    public function __construct(UserService $userService, UserPolicy $userPolicy)
    {
        $this->userService = $userService;
        $this->userPolicy = $userPolicy;
        $this->currentResellerId = 1;
    }

    public function index(Request $request, Response $response): Response
    {
        try {
            $users = $this->userService->getUsersByReseller($this->currentResellerId);

            return $this->success($response, $users->toArray(), Translator::trans('user.customer_list'), 200);
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        try {
            $userId = (int) $args['id'];
            $user = $this->userService->getUserById($userId);

            if (!$this->userPolicy->view($this->currentResellerId, $user, 'reseller')) {
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
            $data['role'] = 'customer';
            $data['reseller_id'] = $this->currentResellerId;
            $user = $this->userService->createUser($data);

            return $this->success($response, $user->toArray(), Translator::trans('user.customer_created'), 201);
        } catch (ApiException $e) {
            return $this->error($response, $e->getMessage(), [], $e->getStatus());
        } catch (\Throwable $e) {
            return $this->error($response, $e->getMessage(), [], 500);
        }
    }
}
