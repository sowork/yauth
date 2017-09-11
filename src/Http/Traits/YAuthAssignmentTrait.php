<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Sowork\YAuth\YAuthAssignment;
use Sowork\YAuth\YAuthItem;

trait YAuthAssignmentTrait
{
    public function assign(YAuthItem $item, $user_id){
        $assign = new YAuthAssignment();
        $assign->item_name = $item->item_name;
        $assign->user_id = $user_id;
        $assign->save();

        return $assign;
    }

    public function revoke(YAuthItem $item, $user_id, $isForceDelete = FALSE){
            return YAuthAssignment::where([
                ['item_name', $item->item_name],
                ['user_id', $user_id]
            ])->when($isForceDelete, function($query){
                return $query->forceDelete();
            }, function ($query){
                return $query->delete();
            });
    }

    public function getAssignments($user_id, $type = NULL, $item_name = FALSE){
        $segmentUrls = config('yauth.user_provider');
        $pathUrl = '/' . Request::path();
        $model = $uid = NULL;
        foreach ($segmentUrls as $segmentUrl){
            if(str_contains($pathUrl, $segmentUrl['url'])){
                $model = $segmentUrl['model'];
                $uid = $segmentUrl['uid'];
            }
        }

        return $model::where($uid, $user_id)
            ->with(['assignments' => function($query) use ($type, $item_name){
                $query->when($type, function ($query) use ($type){
                    return $query->where('item_type', $type);
                })->when($type, function ($query) use ($item_name){
                    return $query->where('item_name', $item_name);
                });
            }])
            ->get();
    }

    public function getUserRoles($user_id){
        return $this->getAssignments($user_id, YAuthItem::TYPE_ROLE);
    }

    public function getUserPermissions($user_id){
        return $this->getAssignments($user_id, YAuthItem::TYPE_PERMISSION);
    }

    public function getUserRole($role_name, $user_id){
        return $this->getAssignments($user_id, YAuthItem::TYPE_ROLE, $role_name);
    }

    public function getUserPermission($permission_name, $user_id){
        return $this->getAssignments($user_id, YAuthItem::TYPE_ROLE, $permission_name);
    }

}