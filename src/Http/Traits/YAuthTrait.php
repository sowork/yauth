<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Sowork\YAuth\YAuthItem;
use Sowork\YAuth\YAuthItemChild;

trait YAuthTrait
{
    public function remove(Model $item, $isForceDelete = FALSE){
        if($isForceDelete){
            return $item->forceDelete();
        }else{
            return $item->delete();
        }
    }

    public function update(Model $item){
        if(!$item->exists)
            return;
        $item->save();
    }

    public function checkAccess($userId, $permissionName){
        $userAssignments = $this->getAssignments($userId);
        if(! $userAssignments->count()){
            return false;
        }

        $this->loadFromCache();
        return $this->checkAccessFromCache($permissionName, $userAssignments);
    }

    public function can($permissionName){
        return $this->checkAccess(Auth::id(), $permissionName);
    }

    /**
     * 加载缓存，如果没有缓存，查询并缓存起来
     */
    protected function loadFromCache(){
        if($this->itemsChilds){
            return;
        }
        $data = Cache::get(config('yauth.cacheKey'));
        if(is_array($data) && isset($data[0], $data[1])){
            list($this->items, $this->itemsChilds) = $data;
            return;
        }

        foreach (YAuthItem::all() as $item){
            $data[0][$item->item_name] = $item;
        }
        foreach (YAuthItemChild::all() as $itemChild){
            $data[1][$itemChild->item_name] = $itemChild;
        }
        list($this->items, $this->itemsChilds) = $data;
        Cache::forever(config('yauth.cacheKey'), $data);
    }

    /**
     * 从缓存中检查权限是否存在
     */
    protected function checkAccessFromCache($permissionName, $assignments){
        if(!isset($this->items[$permissionName])){
            return false;
        }

        // 判断该用户是否拥有顶级角色或权限
        if($assignments->contains('item_name', $permissionName)){
            return true;
        }
        // 判断该用户权限是否为角色包含的权限
        if(!isset($this->itemsChilds[$permissionName])){
            return false;
        }
        $item = $this->itemsChilds[$permissionName];
        foreach ($this->itemsChilds as $itemChild) {
            if($itemChild->lft < $item->lft && $itemChild->rgt > $item->rgt){
                if($assignments->contains('item_name', $itemChild->item_name)){
                    return true;
                }
            }
        }
        return false;
    }

    public function invalidateCache(){
        Cache::forget(config('yauth.cacheKey'));
    }

}