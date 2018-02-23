<?php

namespace Sowork\YAuth;

use Baum\Node;
use Illuminate\Database\Eloquent\Model;
use Sowork\YAuth\Http\Event\ItemRelationCreating;
use Sowork\YAuth\Http\Event\ItemRelationUpdating;

class YAuthItemRelation extends Node
{
    //
    protected $table = 'dictionary';

    protected $guarded = [];

    protected $dispatchesEvents = [
        'creating' => ItemRelationCreating::class,
        'updating' => ItemRelationUpdating::class
    ];

    protected $events = [
        'creating' => ItemRelationCreating::class,
        'updating' => ItemRelationUpdating::class
    ];
}
