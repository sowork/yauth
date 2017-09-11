<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/6
 * Time: 21:44
 */

namespace Sowork\YAuth;


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
}