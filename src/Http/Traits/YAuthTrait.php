<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Sowork\YAuth\YAuthItem;
use Sowork\YAuth\YAuthItemChild;

trait YAuthTrait
{

    public function checkAccess($userId, $permissionName, $provider = null){
        $userAssignments = $this->getAssignments($userId, $provider);
        if(! $userAssignments->count()){
            return false;
        }

        $this->loadFromCache();
        return $this->checkAccessFromCache($permissionName, $userAssignments);
    }

    public function can($permissionName, $provider = null){
        if(!Auth::id()){
            throw new AuthenticationException('Unauthenticated.');
        }
        return $this->checkAccess(Auth::id(), $permissionName, $provider);
    }

    /**
     * 加载缓存，如果没有缓存，查询并缓存起来
     */
    protected function loadFromCache(){
        if($this->itemsChilds)
            return;

        $data = Cache::get(config('yauth.cacheKey'));
        if(is_array($data) && isset($data[0], $data[1])){
            list($this->items, $this->itemsChilds) = $data;
            return;
        }

        $data[0] = $data[1] = [];
        foreach (YAuthItem::all() as $item){
            $this->items[$item->id] = $item;
        }
        foreach (YAuthItemChild::all() as $itemChild){
            $this->itemsChilds[$itemChild->child_item_id][] = $itemChild->parent_item_id;
        }

        Cache::forever(config('yauth.cacheKey'), [$this->items, $this->itemsChilds]);
    }

    /**
     * 从缓存中检查权限是否存在
     */
    protected function checkAccessFromCache($permissionName, $assignments){
        if(! $permission = $this->validatePermission(collect($this->items), $permissionName)){
            return false;
        }

        // 判断该用户是否拥有顶级角色或权限
        if($this->validatePermission($assignments, $permissionName)){
            return true;
        }

        if(!empty($this->itemsChilds[$permission->id])){
            foreach ($this->itemsChilds[$permission->id] as $itemChild) {
                if ($this->checkAccessFromCache($this->items[$itemChild]->item_name, $assignments)) {
                    return true;
                }
            }
        }

        return false;
    }

    private function validatePermission($collectPermission, $permissionName){
        $permission = null;
        $collectPermission->contains(function ($value) use ($permissionName, &$permission){
            if(preg_match('/^' . $value->item_name . '$/', $permissionName)){
                $permission = $value;
                return true;
            }
            return false;
        });
        return $permission;
    }

    public function invalidateCache(){
        Cache::forget(config('yauth.cacheKey'));
    }

    public function autoUpdateCache(){
        if(config('yauth.auto_update_cache')){
            $this->invalidateCache();
        }
    }
}