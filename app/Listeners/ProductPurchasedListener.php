<?php

namespace App\Listeners;

use App\Notifications\PurchaseProduct;

class ProductPurchasedListener
{
    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $event->user->notify(new PurchaseProduct(['name' => $event->user->name]));
    }
}
