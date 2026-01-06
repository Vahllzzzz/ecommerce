<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Mail\OrderPaid;
use Illuminate\Support\Facades\Mail;

class OrderPaidEvent
{

   
    /**
     * Create a new event instance.
     */
     use Dispatchable, SerializesModels;

    public function __construct(public Order $order) 
    {
        
    }


}