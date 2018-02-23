<?php
/**
 * Created by PhpStorm.
 * User: sowork
 * Date: 2018/2/23
 * Time: 22:30
 */

namespace Sowork\YAuth\Http\Event;


use Sowork\YAuth\YAuthItemRelation;

class ItemRelationCreating
{
    public function __construct(YAuthItemRelation $relation)
    {
    }
}