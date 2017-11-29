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

    public function createItems($items){
        if(isset($items[0])){
            return YAuthItem::insert($items);
        }else{
            $item = new YAuthItem;
            $item->item_name = $items->item_name;
            $item->item_type = $items->item_type;
            $item->item_desc = $items->item_desc;
            return $item->save();
        }
    }

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
    public function getItems($type = null, $is_trashed = false, $name=null){
        return YAuthItem::when($is_trashed, function ($query){
            return $query->withTrashed();
        })->when($type, function ($query) use ($type){
            return $query->where('item_type', $type)->get();
        })->when($name, function ($query) use ($name){
            return $query->where('item_name', $name)->first();
        });
    }

    /**
     * 获取所有角色列表
     */
    public function getRoles($is_trashed = false){
        return $this->getItems(YAuthItem::TYPE_ROLE, NULL, $is_trashed);
    }

    /**
     * 获取所有的权限列表
     */
    public function getPermissions($is_trashed = false){
        return $this->getItems(YAuthItem::TYPE_PERMISSION, $is_trashed);
    }

    public function getRole($name, $is_trashed = false){
        $item = $this->getItems(YAuthItem::TYPE_ROLE, $is_trashed, $name);
        return $item->exists && $item->item_type == 1 ? $item : NULL;
    }

    public function getPermission($name, $is_trashed = false){
        $item = $this->getItems(YAuthItem::TYPE_PERMISSION, $is_trashed, $name);
        return $item->exists && $item->item_type == 2 ? $item : NULL;
    }
}