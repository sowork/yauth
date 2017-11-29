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

class YAuthAssignment extends Model
{
    use SoftDeletes;

    public $table = 'yauth_assignments';

    public $primaryKey = 'item_name';

    public $incrementing = FALSE;

    public $dates = ['deleted_at'];

    public function items(){
        return $this->belongsToMany('Sowork\YAuth\YAuthItem', 'yauth_assignments', 'item_name', 'item_name');
    }
}