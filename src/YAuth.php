<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/6
 * Time: 21:44
 */

namespace Sowork\YAuth;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Sowork\YAuth\Http\Interfaces\YAuthAssignmentInterface;
use Sowork\YAuth\Http\Interfaces\YAuthInterface;
use Sowork\YAuth\Http\Interfaces\YAuthItemsChildInterface;
use Sowork\YAuth\Http\Interfaces\YAuthItemsInterface;
use Sowork\YAuth\Http\Traits\YAuthAssignmentTrait;
use Sowork\YAuth\Http\Traits\YAuthItemChildTrait;
use Sowork\YAuth\Http\Traits\YAuthItemTrait;
use Sowork\YAuth\Http\Traits\YAuthTrait;

class YAuth implements YAuthItemsInterface, YAuthItemsChildInterface, YAuthInterface, YAuthAssignmentInterface
{
    use YAuthItemTrait;
    use YAuthItemChildTrait;
    use YAuthTrait;
    use YAuthAssignmentTrait;

    /**
     * 存放items表数据
     */
    protected $items;

    /**
     * 存放itemschilds 表数据
     * @var
     */
    protected $itemsChilds;

    /**
 * 使用的guard
 */
    protected $provider;

    /**
     * 默认运行迁移
     * @var bool
     */
    public static $runsMigrations = true;

    public function __construct()
    {
        $this->provider = config('auth.guards.' . config('auth.defaults.guard') . '.provider');
    }

    /**
     * 配置yauth不使用默认迁移
     */
    public static function ignoreMigrations(){
        static::$runsMigrations = false;

//        dd(new static());
//        return true;
    }

    /**
     * 注册路由
     */
    public static function routes(){
//        $defaultOptions = [
//            'prefix' => 'yauth',
//            'namespace' => 'Sowork\YAuth\Http\Controllers',
//            'middleware' => ['web', 'auth']
//        ];
//
//        Route::group($defaultOptions, function (){
//            Route::get('items/search', 'ItemController@search')->name('items.search');
//            Route::resource('items', 'ItemController');
//        });
    }
}