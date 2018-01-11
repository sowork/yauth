<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:06
 */

namespace Sowork\YAuth\Http\Interfaces;


interface YAuthInterface
{

    /**
     * 检查用户是否有权限
     * @param $user_id
     * @param $permission_name
     * @param array $params
     * @return mixed
     */
    public function checkAccess($user_id, $permission_name, $provider);
}