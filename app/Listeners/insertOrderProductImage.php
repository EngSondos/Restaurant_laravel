<?php

namespace App\Listeners;
use App\Events\OrderCreated;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class insertOrderProductImage
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $order = $event->order;
        foreach ($order->products as $product) {
            $pivot = $order->products()->where('product_id', $product->id)->first()->pivot;
            if (isset($product->image)) {
                $pivot->image = $product->image;
                $pivot->save();
            }
        }    }
}
