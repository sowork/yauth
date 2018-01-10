<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Sowork\YAuth\YAuthAssignment;
use Sowork\YAuth\YAuthItem;

trait YAuthAssignmentTrait
{
    public function assign($user_id, YAuthItem $item, $provider = null){
        $assign = new YAuthAssignment();
        $assign->item_id = $item->id;
        $assign->user_id = $user_id;
        $assign->provider = $provider ?: $this->provider;
        $assign->save();
        $this->autoUpdateCache();

        return $assign;
    }

    public function revoke($user_id, YAuthItem $item, $provider = null, $isForceDelete = false){
        YAuthAssignment::where([
            ['item_id', $item->id],
            ['user_id', $user_id],
            ['provider', $provider ?: $this->provider],
        ])->when($isForceDelete, function($query){
            return $query->forceDelete();
        }, function ($query){
            return $query->delete();
        });

        $this->autoUpdateCache();
        return true;
    }

    public function getAssignments($user_id, $provider = null, $type = false, $is_show_del = false, $item_name = false){
        return YAuthAssignment::select('yauth_assignments.*', 'yauth_items.item_name', 'yauth_items.item_type', 'yauth_items.item_desc')
            ->join('yauth_items', 'yauth_assignments.item_id', '=', 'yauth_items.id')
            ->where([
                ['yauth_assignments.user_id', $user_id],
                ['yauth_assignments.provider', $provider ?: $this->provider]
            ])->when($type, function ($query) use ($type){
                return $query->where('yauth_items.item_type', $type);
            })->when($is_show_del, function ($query){
                return $query->withTrashed();
            })->when($item_name, function ($query) use ($item_name){
                return $query->where('yauth_items.item_name', $item_name);
            })->get();
    }

    public function getUserRoles($user_id, $provider = null, $is_show_del = false){
        return $this->getAssignments($user_id, $provider ?: $this->provider, YAuthItem::TYPE_ROLE, $is_show_del);
    }

    public function getUserPermissions($user_id, $provider = null, $is_show_del = false){
        return $this->getAssignments($user_id, $provider ?: $this->provider, YAuthItem::TYPE_PERMISSION, $is_show_del);
    }

    public function getUserRole($user_id, $provider = null,  $item_name = false, $is_show_del = false){
        return $this->getAssignments($user_id, $provider ?: $this->provider, YAuthItem::TYPE_ROLE, $is_show_del, $item_name);
    }

    public function getUserPermission($user_id, $provider = null, $item_name = false, $is_show_del = false){
        return $this->getAssignments($user_id, $provider ?: $this->provider, YAuthItem::TYPE_ROLE, $is_show_del, $item_name);
    }

}