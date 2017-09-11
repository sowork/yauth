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

class YAuthItem extends Model
{
    use SoftDeletes;

    const TYPE_ROLE = 1;

    const TYPE_PERMISSION = 2;

    public $table = 'yauth_items';

    public $primaryKey = 'item_name';

    public $incrementing = FALSE;

    public $dates = ['deleted_at'];

    public $events = [
        'deleting' => 'Sowork\YAuth\Http\Events\YAuthItemDeleting',
    ];


    /**
     * 根据type返回相应的items列表
     * @param $type
     */
    public static function getItems($type){
        return self::where('item_type', $type)->get();
    }

    /**
     * 根据name返回对应的权限或角色
     */
    public static function getItem($name){
        return self::find($name);
    }

    /**
     * 返回
     */
    public function roles(){
        return $this->belongsToMany('Sowork\YAuth\YAuthItem', 'yauth_items_childs', 'item_name', 'item_name');
    }
}