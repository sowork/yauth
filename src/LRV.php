<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/8/28
 * Time: 22:14
 */

namespace Sowork\YAuth;


use http\Exception\InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LRV extends Model
{
    /**
     * 添加一个LRV item
     */
    public static function addLRV(Model $instance, Model $node, $parent_id){
        $parent = null;
        if(!$parent_id){
            $maxRight = $instance->withTrashed()->max('rgt');
            if($maxRight){
                $instance->lft = $maxRight + 1;
                $instance->rgt = $instance->lft + 1;
            }else{
                $instance->lft = 1;
                $instance->rgt = 2;
            }

            $instance->item_id = $node->id;
            $instance->parent_id = $parent_id;
            $instance->depth = 0;
        }else{
            $parent = $instance->where($instance->getKeyName(), $parent_id)->first();
            $childNode = $instance->where([
                ['lft', '>', $parent->lft],
                ['rgt', '<', $parent->rgt],
                ['item_id', $node->id]
            ])->withTrashed()
                ->first();

            if($childNode){
                throw new InvalidArgumentException('Cannot repeat add ' . $node->item_name . ' to ' . $parent->item_name);
            }

            // 添加子节点
            $instance->lft = $parent->rgt;
            $instance->rgt = $parent->rgt + 1;
            $instance->item_id = $node->id;
            $instance->parent_id = $parent_id;
            $instance->depth = $parent->depth + 1;
        }

        return DB::transaction(function () use ($instance, $parent){
            if($parent){
                // 所有右值满足>=$node['right'] 值 +=2
                $instance->where('rgt', '>=', $parent->rgt)
                    ->withTrashed()
                    ->increment('rgt', 2);

                // 所有左值修改>$node['right'] 值 +=2
                $instance->where('lft', '>', $parent->rgt)
                    ->withTrashed()
                    ->increment('lft', 2);
            }

            return $instance->save();
        });
    }

    /**
     * 修改一个LRV item
     */
    public static function updateLRV(){

    }

    /**
     * 删除一个LRV item
     */
    public static function deleteLRV(Model $node, $isForceDelete = FALSE){
        if(!$node->exists)
            return FALSE;
        // 删除子节点和父节点
        $node->where([
            ['lft', '>=', $node['lft']],
            ['rgt', '<=', $node['rgt']]
        ])->when($isForceDelete, function ($query) use ($node){
            $count = $query->withTrashed()->forceDelete();
            $node->where('lft', '>', $node['lft'])
                ->withTrashed()
                ->decrement('lft', ($count * 2));
            $node->where('rgt', '>', $node['rgt'])
                ->withTrashed()
                ->decrement('rgt', ($count * 2));
            return TRUE;
        }, function($query){
            return $query->delete();
        });
        return FALSE;
    }
}