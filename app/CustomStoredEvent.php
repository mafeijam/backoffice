<?php

namespace App;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class CustomStoredEvent extends EloquentStoredEvent
{
    public static function boot()
    {
        parent::boot();

        static::creating(function(CustomStoredEvent $storedEvent) {
            $storedEvent->meta_data['user_id'] = auth()->id();
        });
    }
}
