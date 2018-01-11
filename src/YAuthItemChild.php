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

    public $guarded = [];
}