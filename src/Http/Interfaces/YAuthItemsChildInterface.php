<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:06
 */

namespace Sowork\YAuth\Http\Interfaces;


use Sowork\YAuth\YAuthItem;

interface YAuthItemsChildInterface
{

    /**
     * 增加一个item作为另一个item的子节点
     * @param $parent
     * @param $child
     * @return mixed
     */
    public function addChild(YAuthItem $parent, YAuthItem $child);

    /**
     * 从它的父节点中删除一个子节点
     * @param $parentItem
     * @param $childItem
     * @param bool $isForceDelete
     * @return mixed
     */
    public function removeChild($parentItem, $childItem, $isForceDelete = false);

    /**
     * 从父节点上删除所有子节点
     * @param $parentItem
     * @param bool $isForceDelete
     * @return mixed
     */
    public function removeChildren($parentItem, $isForceDelete = false);

}