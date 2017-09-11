<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 22:46
 */

return [
    /**
     *  当url以/开始，默认提供权限的模型为 App\User
     *  当url以/admin 开始，默认提供权限的模型为 App\Admin
     *  当多个表的用户同时使用权限时，那么多个表的用户ID必须是唯一的
     */
    'user_provider' => [
        [
            'url' => '/',
            'model' => \App\User::class,
            'uid' => 'id',
        ],
//        [
//            'url' => '/admin',
//            'model' => App\Admin::class,
//            'uid' => 'user_id'
//        ]
    ],

    'cacheKey' => 'yauth-rbac',
];