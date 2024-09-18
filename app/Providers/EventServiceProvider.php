<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * El array de listeners de eventos para la aplicación.
     *
     * @var array
     */
    protected $listen = [
        // 'App\Events\SomeEvent' => [
        //     'App\Listeners\EventListener',
        // ],
    ];

    /**
     * Registra cualquier evento para tu aplicación.
     */
    public function boot()
    {
        parent::boot();

        //
    }
}