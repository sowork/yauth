<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/6
 * Time: 21:44
 */

namespace Sowork\YAuth;


class YAuth
{

    /**
     * 默认运行迁移
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * 配置yauth不使用默认迁移
     */
    public static function ignoreMigrations(){
        static::$runsMigrations = false;
    }

    /**
     * 注册路由
     */
    public static function routes(){
        // todo 视图路由
    }
}