<?php

return [
    'validation.required_name' => 'Name is required.',
    'validation.name_min' => 'Name must be at least 2 characters long.',
    'validation.name_max' => 'Name cannot exceed 100 characters.',
    'validation.email_required' => 'Email is required.',
    'validation.email_invalid' => 'The email address is invalid.',
    'validation.email_unique' => 'This email is already in use.',
    'validation.password_required' => 'Password is required.',
    'validation.password_min' => 'Password must be at least 8 characters long.',
    'validation.invalid' => 'Validation failed.',

    'user.not_found' => 'User not found.',
    'user.email_exists' => 'This email is already registered.',
    'user.invalid_payload' => 'Name, email and password are required.',
    'user.list' => 'User list.',
    'user.profile' => 'User profile.',
    'user.created' => 'User created successfully.',
    'user.updated' => 'User updated successfully.',
    'user.deleted' => 'User deleted successfully.',
    'user.customer_list' => 'Customer list.',
    'user.customer_created' => 'Customer created successfully.',

    'auth.admin_only' => 'Only admin can perform this action.',
    'auth.reseller_only' => 'Only reseller can perform this action.',
    'auth.customer_only' => 'Only customer can perform this action.',
    'auth.access_denied' => 'You do not have permission to access this resource.',
    'auth.invalid_token' => 'Invalid token.',
    'auth.invalid_token_format' => 'Invalid token format.',
    'auth.token_missing' => 'Authorization token is missing.',
    'error.internal' => 'Internal server error.',
    'error.validation' => 'Validation error.',
];
