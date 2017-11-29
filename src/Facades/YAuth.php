<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/4
 * Time: 23:51
 */

namespace Sowork\YAuth\Facades;


use Illuminate\Support\Facades\Facade;

class YAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'yauth';
    }
}