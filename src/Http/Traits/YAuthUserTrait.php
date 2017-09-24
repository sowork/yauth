<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/16
 * Time: 23:18
 */

namespace Sowork\YAuth\Http\Traits;


use Illuminate\Support\Facades\Auth;
use Sowork\YAuth\Facades\YAuth;

trait YAuthUserTrait
{
    /**
     * 用户和角色关联关系
     * @return mixed
     */
    public function assignments(){
        return $this->belongsToMany('Sowork\YAuth\YAuthItem', 'yauth_assignments', 'user_id', 'item_name');
    }

    public function getGuard(){
        return $this->getTable();
    }

}