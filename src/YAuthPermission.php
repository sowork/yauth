<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2017/7/23
 * Time: 12:32
 */

namespace Sowork\YAuth;


class YAuthPermission extends YAuthItem
{
    public function __construct()
    {
        parent::__construct();
        $this->item_type = self::TYPE_PERMISSION;
    }
}