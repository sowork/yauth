<?php

namespace Sowork\YAuth\Http\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Sowork\YAuth\LRV;
use Sowork\YAuth\YAuthAssignment;
use Sowork\YAuth\YAuthItem;
use Sowork\YAuth\YAuthItemChild;

class YAuthItemDeleting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(YAuthItem $item)
    {
        // 外键约束处理
        $ditem = YAuthItemChild::where('item_name', $item->item_name)->withTrashed()->first();
        LRV::deleteLRV($ditem, $item->isForceDeleting());
        YAuthAssignment::where('item_name', $item->item_name)
            ->when($item->isForceDeleting(), function ($query){
                return $query->withTrashed()
                    ->forceDelete();
            }, function ($query){
                return $query->delete();
            });
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
