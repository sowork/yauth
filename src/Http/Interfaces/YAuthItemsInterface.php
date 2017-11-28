<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:06
 */

namespace Sowork\YAuth\Http\Interfaces;


use Sowork\YAuth\YAuthItem;

interface YAuthItemsInterface
{


    /**
     * 创建权限
     * @param $permission_name 权限名称
     * @return YAuthItem
     */
    public function createPermission($permission_name);

    /**
     * 创建角色
     * @param $role_name 角色名称
     * @return YAuthItem
     */
    public function createRole($role_name);

    /**
     * 添加权限或角色
     * @param $yAuthItem YAuthItem
     */
    public function add(YAuthItem $yAuthItem);

    /**
     * 获取所有Item
     */
    public function getItems($item_type = null, $item_name = null, $is_trashed=true);

    /**
     * 获取所有角色列表
     */
    public function getRoles();

    /**
     * 获取所有的权限列表
     */
    public function getPermissions();

    /**
     * 获取一个角色
     */
    public function getRole($name);

    /**
     * 获取一个权限
     */
    public function getPermission($name);

}