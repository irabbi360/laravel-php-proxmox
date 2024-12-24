<?php

namespace Irabbi360\Proxmox;

use Illuminate\Support\ServiceProvider;
use Irabbi360\Proxmox\ProxmoxApi;

class ProxmoxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(ProxmoxApi::class, function ($app) {
            return new ProxmoxApi(
                config('proxmox.hostname'),
                config('proxmox.username'),
                config('proxmox.password')
            );
        });
    }

    public function boot()
    {
        // Publish the config file
        $this->publishes([
            __DIR__ . '/config/proxmox.php' => config_path('proxmox.php'),
        ]);
    }
}
