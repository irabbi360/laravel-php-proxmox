<?php

namespace Irabbi360\Proxmox\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Irabbi360\Proxmox\LaravelProxmox
 */
class ProxmoxApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Irabbi360\Proxmox\ProxmoxApi::class;
    }
}
