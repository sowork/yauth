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

    public function getAssignments($user_id, $type = NULL, $is_show_del = FALSE, $item_name = FALSE){
        return YAuthAssignment::join('yauth_items', 'yauth_assignments.item_name', '=', 'yauth_items.item_name')
            ->where([
                ['yauth_assignments.user_id', $user_id],
                ['yauth_assignments.guard_table', Auth::user()->getGuard()]
            ])->when($type, function ($query) use ($type){
                return $query->where('yauth_items.item_type', $type);
            })->when($is_show_del, function ($query){
                return $query->withTrashed();
            })->when($item_name, function ($query) use ($item_name){
                return $query->where('yauth_items.item_name', $item_name);
            })->get();
    }

    public function getUserRoles($user_id, $is_show_del = FALSE){
        return $this->getAssignments($user_id, YAuthItem::TYPE_ROLE, $is_show_del);
    }

    public function getUserPermissions($user_id, $is_show_del = FALSE){
        return $this->getAssignments($user_id, YAuthItem::TYPE_PERMISSION, $is_show_del);
    }

    public function getUserRole($user_id, $item_name = FALSE, $is_show_del = FALSE){
        return $this->getAssignments($user_id, YAuthItem::TYPE_ROLE, $is_show_del, $item_name);
    }

    public function getUserPermission($user_id, $item_name = FALSE, $is_show_del = FALSE){
        return $this->getAssignments($user_id, YAuthItem::TYPE_ROLE, $is_show_del, $item_name);
    }

}