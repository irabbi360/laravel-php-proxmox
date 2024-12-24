<?php

namespace Irabbi360\Proxmox;

use Illuminate\Support\ServiceProvider;

class ProxmoxServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(
            __DIR__.'/../config/proxmox.php', 'proxmox'
        );

        // Register the main class
        $this->app->singleton(ProxmoxApi::class, function ($app) {
            $config = $app['config']['proxmox'];

            return new ProxmoxApi(
                $config['hostname'],
                $config['username'],
                $config['password'],
                $config['realm'],
                $config['port']
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Publish the config file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/proxmox.php' => config_path('proxmox.php'),
            ], 'proxmox-config');
        }
    }
}
