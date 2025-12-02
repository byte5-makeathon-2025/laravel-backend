<?php

namespace App\Listeners;

use App\Events\WishCreatedEvent;

class WishNotFromGermanyListener
{
    /**
     * Handle the event.
     */
    public function handle(WishCreatedEvent $event): void
    {
        $wish = $event->wish;

        if (
            $wish->latitude < 47.2 ||
            $wish->latitude > 55.0 ||
            $wish->longitude < 8.0 ||
            $wish->longitude > 15.0
        ) {
          $wish->update([
              'status' => 'pending'
          ]);
        } else {
            $wish->update([
                'status' => 'granted'
            ]);
        }
    }
}
