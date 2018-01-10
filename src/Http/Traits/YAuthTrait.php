<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Sowork\YAuth\YAuthItem;
use Sowork\YAuth\YAuthItemChild;

trait YAuthTrait
{
    public function remove(Model $item, $isForceDelete = FALSE){
        if($isForceDelete){
            DB::transaction(function () use ($item, $isForceDelete){
                $item->forceDelete();
            });
        }else{
            $item->delete();
        }

        $this->autoUpdateCache();
        return true;
    }

    public function update(Model $item){
        if(!$item->exists)
            return;

        $item->save();
        $this->autoUpdateCache();

        return true;
    }

    public function checkAccess($userId, $permissionName, $provider = null){
        $userAssignments = $this->getAssignments($userId, $provider ?: $provider);
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
        // 只要调用过，同一个操作中下次不再调用
        if($this->itemsChilds){
            return;
        }
        $data = Cache::get(config('yauth.cacheKey'));
        if(is_array($data) && isset($data[0], $data[1])){
            list($this->items, $this->itemsChilds) = $data;
            return;
        }

        $data[0] = $data[1] = [];
        foreach (YAuthItem::all() as $item){
            $data[0][$item->id] = $item;
        }
        foreach (YAuthItemChild::all() as $itemChild){
            $data[1][$itemChild->item_id] = $itemChild;
        }

        list($this->items, $this->itemsChilds) = $data;
        Cache::forever(config('yauth.cacheKey'), $data);
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

        // 判断该用户权限是否为角色包含的权限
//        if(!isset($this->itemsChilds[$permission->id]) || $this->itemsChilds[$permission->id]->item_type !== YAuthItem::TYPE_ROLE){
        if(!isset($this->itemsChilds[$permission->id])){
            return false;
        }
        $item = $this->itemsChilds[$permission->id];
        foreach ($this->itemsChilds as $itemChild) {
            if($itemChild->lft < $item->lft && $itemChild->rgt > $item->rgt){
                $temp = $this->items[$itemChild->item_id];
                if($this->validatePermission($assignments, $temp->item_name)){
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