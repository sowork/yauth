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

interface YAuthAssignmentInterface
{
    /**
     * 给用户赋予权限
     * @param YAuthItem $item
     * @param $user_id
     * @return mixed
     */
    public function assign(YAuthItem $item, $user_id);

    /**
     * 删除用户权限
     * @param YAuthItem $item
     * @param $user_id
     * @return mixed
     */
    public function revoke(YAuthItem $item, $user_id);

    /**
     * 获取给用户分配角色和权限
     * @param $user_id
     * @param bool $type
     * @param $item_name
     * @return mixed
     */
    public function getAssignments($user_id, $type = NULL, $is_show_del = FALSE, $item_name = FALSE);

    /**
     * 只获取用户分配角色
     */
    public function getUserRoles($user_id, $is_show_del = FALSE);

    /**
     * 只获取用户分配权限
     */
    public function getUserPermissions($user_id, $is_show_del = FALSE);

    /**
     * 获取用户某一个角色
     * @param $user_id
     * @param $role_name
     * @return mixed
     */
    public function getUserRole($user_id, $item_name = FALSE, $is_show_del = FALSE);

    /**
     * 获取用户某一个权限
     * @param $user_id
     * @param $permission_name
     * @return mixed
     */
    public function getUserPermission($user_id, $item_name = FALSE, $is_show_del = FALSE);
}