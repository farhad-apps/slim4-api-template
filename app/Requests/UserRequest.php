<?php

namespace App\Requests;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Filesystem\Filesystem;
use App\Exceptions\ApiException;

class UserRequest
{
    public static function validateStore(array $data): array
    {
        $loader = new ArrayLoader();
        $translator = new Translator($loader, 'en');
        $factory = new ValidatorFactory($translator, new Filesystem());

        $validator = $factory->make($data, [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required' => 'نام الزامی است.',
            'name.min' => 'نام باید حداقل 2 کاراکتر باشد.',
            'name.max' => 'نام نمی‌تواند بیش از 100 کاراکتر باشد.',
            'email.required' => 'ایمیل الزامی است.',
            'email.email' => 'ایمیل معتبر نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'password.required' => 'کلمه عبور الزامی است.',
            'password.min' => 'کلمه عبور باید حداقل 8 کاراکتر باشد.',
        ]);

        if ($validator->fails()) {
            throw new ApiException(
                'خطای اعتبارسنجی',
                422,
                $validator->errors()->toArray()
            );
        }

        return $validator->validated();
    }

    public static function validateUpdate(array $data, int $userId): array
    {
        $loader = new ArrayLoader();
        $translator = new Translator($loader, 'en');
        $factory = new ValidatorFactory($translator, new Filesystem());

        $validator = $factory->make($data, [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $userId],
            'password' => ['sometimes', 'string', 'min:8'],
        ], [
            'name.min' => 'نام باید حداقل 2 کاراکتر باشد.',
            'name.max' => 'نام نمی‌تواند بیش از 100 کاراکتر باشد.',
            'email.email' => 'ایمیل معتبر نیست.',
            'email.unique' => 'این ایمیل قبلاً ثبت شده است.',
            'password.min' => 'کلمه عبور باید حداقل 8 کاراکتر باشد.',
        ]);

        if ($validator->fails()) {
            throw new ApiException(
                'خطای اعتبارسنجی',
                422,
                $validator->errors()->toArray()
            );
        }

        return $validator->validated();
    }
}
