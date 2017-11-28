<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Sowork\YAuth\YAuthItem;
use Sowork\YAuth\YAuthPermission;
use Sowork\YAuth\YAuthRole;

trait YAuthItemTrait
{
    public function createPermission($permission_name){
        $permission = new YAuthPermission();
        $permission->item_name = $permission_name;

        return $permission;
    }

    public function createRole($role_name){
        $role = new YAuthRole();
        $role->item_name = $role_name;

        return $role;
    }

    public function add(YAuthItem $yAuthManager){
        return $yAuthManager->save();
    }

    /**
     * 根据type返回相应的items列表
     * @param $type
     */
    public function getItems($type = null, $name=null, $is_trashed = true){
        return YAuthItem::when($is_trashed, function ($query){
            return $query->withTrashed();
        })->when($type, function ($query) use ($type){
            return $query->where('item_type', $type);
        })->when($name, function ($query) use ($name){
            return $query->where('item_name', $name);
        })->get();
    }

    /**
     * 获取所有角色列表
     */
    public function getRoles(){
        return $this->getItems(YAuthItem::TYPE_ROLE);
    }

    /**
     * 获取所有的权限列表
     */
    public function getPermissions(){
        return $this->getItems(YAuthItem::TYPE_PERMISSION);
    }

    public function getRole($name){
        $item = $this->getItems(YAuthItem::TYPE_ROLE, $name);
        return $item->exists && $item->item_type == 1 ? $item : NULL;
    }

    public function getPermission($name){
        $item = $this->getItems(YAuthItem::TYPE_PERMISSION, $name);
        return $item->exists && $item->item_type == 2 ? $item : NULL;
    }
}