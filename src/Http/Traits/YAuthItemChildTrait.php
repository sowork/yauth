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

        // 查找当前父节点是否存在
        $parentItem = YAuthItemChild::where('item_id', $parent->id)->first();
        DB::transaction(function () use ($parentItem, $parent, $child) {
            if(!$parentItem){ // 添加顶级item
                $item = YAuthItemChild::addItem($parent);
                YAuthItemChild::addItem($child, $item->id);
            }else{ // 添加子节点
                YAuthItemChild::addItem($child, $parentItem->id);
            }
        });
        $this->autoUpdateCache();

        return TRUE;
    }

}