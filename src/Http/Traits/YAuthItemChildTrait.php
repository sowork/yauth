<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Illuminate\Support\Facades\DB;
use Psr\Log\InvalidArgumentException;
use Sowork\YAuth\YAuthItem;
use Sowork\YAuth\YAuthItemChild;
use Sowork\YAuth\YAuthPermission;
use Sowork\YAuth\YAuthRole;

trait YAuthItemChildTrait
{
    public function addChild(YAuthItem $parent, YAuthItem $child){
        if($parent->item_name == $child->item_name){
            throw new InvalidArgumentException('Cannot add ' . $parent->item_name . ' as a child of itself');
        }

        if($parent instanceof YAuthPermission && $child instanceof YAuthRole){
            throw new InvalidArgumentException('Cannot add a role as a child of a permission');
        }

        if($parent instanceof YAuthPermission && $child instanceof YAuthPermission){
            throw new InvalidArgumentException('Cannot add a permission as a child of a permission');
        }

        YAuthItemChild::create(['parent_item_id' => $parent->id, 'child_item_id' => $child->id]);
        $this->autoUpdateCache();

        return TRUE;
    }

    public function removeChild($parentItem, $childItem, $isForceDelete = false){
        return YAuthItemChild::where([
            ['parent_item_id', $parentItem->id],
            ['child_item_id', $childItem->id]
        ])->when($isForceDelete, function ($query){
            return $query->withTrashed()->forceDelete();
        }, function ($query){
            return $query->delete();
        });
    }

    public function removeChildren($parentItem, $isForceDelete = false){
        return YAuthItemChild::where([
            ['parent_item_id', $parentItem->id],
        ])->when($isForceDelete, function ($query){
            return $query->withTrashed()->forceDelete();
        }, function ($query){
            return $query->delete();
        });
    }
}