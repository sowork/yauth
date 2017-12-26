<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:06
 */

namespace Sowork\YAuth\Http\Interfaces;


use Illuminate\Database\Eloquent\Model;
use Sowork\YAuth\YAuthItem;

interface YAuthInterface
{
    /**
     * add 操作分别为YAuthItem的add方法和YAuthItem的addChild方法
     */

    /**
     * 移除一个权限或角色
     */
    public function remove(Model $item);

    /**
     * 修改角色、权限
     */
    public function update(Model $item);

    /**
     * 检查用户是否有权限
     * @param $user_id
     * @param $permission_name
     * @param array $params
     * @return mixed
     */
    public function checkAccess($user_id, $permission_name, $guard_name);
}