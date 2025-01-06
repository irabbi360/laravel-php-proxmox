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

        // Register Proxmox services
        $services = [
            'proxmox-node' => ProxmoxNodeVm::class,
            'proxmox-cluster' => ProxmoxCluster::class,
            'proxmox-storage' => ProxmoxStorage::class,
            'proxmox-pools' => ProxmoxPools::class,
            'proxmox-access' => ProxmoxAccess::class,
        ];

        foreach ($services as $alias => $class) {
            $this->app->singleton($alias, function ($app) use ($class) {
                $config = $app['config']['proxmox'];

                return new $class(
                    $config['hostname'],
                    $config['username'],
                    $config['password'],
                    $config['realm'],
                    $config['port']
                );
            });
        }
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
