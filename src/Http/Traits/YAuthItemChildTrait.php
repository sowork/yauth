<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


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
        // 查找当前父节点是否存在
        $itemChild = YAuthItemChild::where('item_name', $parent->item_name)->first();
        if(!$itemChild){ // 添加顶级item
            $node = YAuthItemChild::addItem($parent);
            YAuthItemChild::addItem($child, $node->ichild_id);
        }else{ // 添加子节点
            YAuthItemChild::addItem($child, $itemChild->ichild_id);
        }

        return TRUE;
    }

}