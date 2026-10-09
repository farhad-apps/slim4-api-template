<?php

namespace App\Requests;

use App\Exceptions\ApiException;
use App\Helpers\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator as IlluminateTranslator;
use Illuminate\Validation\Factory as ValidatorFactory;

class UserRequest
{
    public static function validateStore(array $data): array
    {
        $loader = new ArrayLoader();
        $translator = new IlluminateTranslator($loader, Translator::getLocale());
        $factory = new ValidatorFactory($translator, new Filesystem());

        $validator = $factory->make($data, [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required' => Translator::trans('validation.required_name'),
            'name.min' => Translator::trans('validation.name_min'),
            'name.max' => Translator::trans('validation.name_max'),
            'email.required' => Translator::trans('validation.email_required'),
            'email.email' => Translator::trans('validation.email_invalid'),
            'email.unique' => Translator::trans('validation.email_unique'),
            'password.required' => Translator::trans('validation.password_required'),
            'password.min' => Translator::trans('validation.password_min'),
        ]);

        if ($validator->fails()) {
            throw new ApiException(
                Translator::trans('error.validation'),
                422,
                $validator->errors()->toArray()
            );
        }

        return $validator->validated();
    }

    public static function validateUpdate(array $data, int $userId): array
    {
        $loader = new ArrayLoader();
        $translator = new IlluminateTranslator($loader, Translator::getLocale());
        $factory = new ValidatorFactory($translator, new Filesystem());

        $validator = $factory->make($data, [
            'name' => ['sometimes', 'string', 'min:2', 'max:100'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $userId],
            'password' => ['sometimes', 'string', 'min:8'],
        ], [
            'name.min' => Translator::trans('validation.name_min'),
            'name.max' => Translator::trans('validation.name_max'),
            'email.email' => Translator::trans('validation.email_invalid'),
            'email.unique' => Translator::trans('validation.email_unique'),
            'password.min' => Translator::trans('validation.password_min'),
        ]);

        if ($validator->fails()) {
            throw new ApiException(
                Translator::trans('error.validation'),
                422,
                $validator->errors()->toArray()
            );
        }

        return $validator->validated();
    }
}
