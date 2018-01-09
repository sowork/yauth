<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/6
 * Time: 23:28
 */

namespace Sowork\YAuth;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class YAuthItemChild extends Model
{
    use SoftDeletes;

    public $table = 'yauth_items_childs';

    public $timestamps = FALSE;

    public $guarded = ['id', 'parent_id', 'lft', 'rgt', 'depth'];

    public static function addItem(YAuthItem $node, $parent_id = 0){
        return tap(self::newModelInstance(), function($instance) use ($node, $parent_id){
            LRV::addLRV($instance, $node, $parent_id);
        });
    }
}