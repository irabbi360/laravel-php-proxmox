<?php

namespace Irabbi360\Proxmox\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Irabbi360\Proxmox\LaravelProxmox
 */
class ProxmoxNode extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'proxmox-node';
    }
}
