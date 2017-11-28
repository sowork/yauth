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
use Illuminate\Support\Facades\Request;
use Sowork\YAuth\YAuthAssignment;
use Sowork\YAuth\YAuthItem;

trait YAuthAssignmentTrait
{
    public function assign(YAuthItem $item, $user_id){
        $assign = new YAuthAssignment();
        $assign->item_name = $item->item_name;
        $assign->user_id = $user_id;
        $assign->guard_table = Auth::user()->getGuard();
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

    public function getAssignments($type = NULL, $item_name = FALSE){
        return YAuthAssignment::with('items')
            ->withTrashed()
            ->where('user_id', Auth::id())
            ->when($type, function ($query) use ($type){
                return $query->where('item_type', $type);
            })->when($item_name, function ($query) use ($item_name){
                return $query->where('item_name', $item_name);
            })->where('guard_table', Auth::user()->getGuard())
            ->get();
    }

    public function getUserRoles(){
        return $this->getAssignments(YAuthItem::TYPE_ROLE);
    }

    public function getUserPermissions(){
        return $this->getAssignments(YAuthItem::TYPE_PERMISSION);
    }

    public function getUserRole($role_name){
        return $this->getAssignments(YAuthItem::TYPE_ROLE, $role_name);
    }

    public function getUserPermission($permission_name){
        return $this->getAssignments(YAuthItem::TYPE_ROLE, $permission_name);
    }

}