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

    public $dates = ['deleted_at'];

    /**
     * 兼容laravel5.4
     * @var array
     */
    public $events = [
        'deleting' => 'Sowork\YAuth\Http\Events\YAuthItemDeleting',
    ];

    public $dispatchesEvents = [
        'deleting' => 'Sowork\YAuth\Http\Events\YAuthItemDeleting',
    ];

    /**
     * 返回
     */
    public function roles(){
        return $this->belongsToMany('Sowork\YAuth\YAuthItem', 'yauth_items_childs', 'item_name', 'item_name');
    }
}