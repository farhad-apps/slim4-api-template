<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Exceptions\ApiException;

class UserService
{
    /**
     * دریافت تمام کاربران
     */
    public function getAllUsers()
    {
        return User::all();
    }

    /**
     * دریافت کاربر به ID
     */
    public function getUserById(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            throw new ApiException('کاربر یافت نشد.', 404);
        }

        return $user;
    }

    /**
     * دریافت کاربران یک Reseller
     */
    public function getUsersByReseller(int $resellerId)
    {
        return User::where('reseller_id', $resellerId)
            ->where('role', 'customer')
            ->get();
    }

    /**
     * Query پیچیده: آمار Resellers
     * Join با Orders و محاسبه تعداد، مجموع، و میانگین
     */
    public function getResellerStatistics()
    {
        return User::where('role', 'reseller')
            ->with([
                'customers' => function ($query) {
                    $query->count(); // تعداد مشتریان
                },
                'orders' => function ($query) {
                    $query->where('status', 'completed'); // فقط Order های تکمیل شده
                }
            ])
            ->leftJoin('orders', function ($join) {
                $join->on('users.id', '=', 'orders.user_id')
                    ->where('orders.status', '=', 'completed');
            })
            ->selectRaw('users.id, users.name, users.email')
            ->selectRaw('COUNT(DISTINCT orders.id) as total_orders')
            ->selectRaw('SUM(orders.total_price) as total_sales')
            ->selectRaw('AVG(orders.total_price) as avg_order_value')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_sales')
            ->get();
    }

    /**
     * Query پیچیده: کاربران بر اساس شروط متعدد
     * دریافت Customers که:
     * - حداقل 5 Order داشتند
     * - مجموع فروش بیش از 1000
     * - در ماه آخر خرید کردند
     */
    public function getHighValueCustomers()
    {
        return User::where('role', 'customer')
            ->leftJoin('orders', 'users.id', '=', 'orders.user_id')
            ->where('orders.status', '=', 'completed')
            ->where('orders.created_at', '>=', now()->subMonth())
            ->selectRaw('users.id, users.name, users.email, users.reseller_id')
            ->selectRaw('COUNT(orders.id) as order_count')
            ->selectRaw('SUM(orders.total_price) as total_spent')
            ->selectRaw('MAX(orders.created_at) as last_order_date')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.reseller_id')
            ->havingRaw('COUNT(orders.id) >= 5')
            ->havingRaw('SUM(orders.total_price) > 1000')
            ->orderByDesc('total_spent')
            ->get();
    }

    /**
     * ایجاد کاربر جدید
     */
    public function createUser(array $data): User
    {
        // Validation ساده
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            throw new ApiException('نام، ایمیل و کلمه عبور الزامی هستند.', 422);
        }

        if (User::where('email', $data['email'])->exists()) {
            throw new ApiException('این ایمیل قبلاً ثبت شده است.', 422);
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role'] ?? 'customer',
            'reseller_id' => $data['reseller_id'] ?? null,
        ]);

        return $user;
    }

    /**
     * بروزرسانی کاربر
     */
    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $user->update($data);

        return $user;
    }

    /**
     * حذف کاربر
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->getUserById($id);
        return $user->delete();
    }
}
