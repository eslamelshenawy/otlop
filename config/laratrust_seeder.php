<?php

return [
    'role_structure' => [
        'super_admin' => [
            'admins' => 'c,r,u,d',
            'cities' => 'c,r,u,d',
            'vendors' => 'c,r,u,d',
            'restaurants' => 'c,r,u,d',
            'account_admin' => 'c,r,u,d',
            'question' => 'c,r,u,d',
            'privacy' => 'c,r,u,d',
            'package' => 'c,r,u,d',
            'blog' => 'c,r,u,d',
            'category' => 'c,r,u,d',
            'request' => 'c,r,u,d',
        ],
        'admin' => [],
        'vendor' => [
            'user_vendors' => 'c,r,u,d',
            'location' => 'c,r,u,d',
            'menu' => 'c,r,u,d',
            'account_vendor' => 'c,r,u,d',
            'meal_food' => 'c,r,u,d',
            'offer' => 'c,r,u,d',
        ],

    ],
    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
