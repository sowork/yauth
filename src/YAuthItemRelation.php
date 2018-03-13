<?php

namespace Sowork\YAuth;

use Baum\Node;
use Illuminate\Database\Eloquent\Model;
use Sowork\YAuth\Http\Event\ItemRelationCreating;
use Sowork\YAuth\Http\Event\ItemRelationUpdating;

class YAuthItemRelation extends Node
{
    //
    protected $table = 'yauth_item_relation';

    protected $guarded = [];
}
