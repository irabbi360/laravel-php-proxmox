<?php

namespace Irabbi360\Proxmox;

use Irabbi360\Proxmox\Proxmox;

class ProxmoxCluster extends Proxmox
{
    /**
     * @throws \Exception
     */
    public function cluster()
    {
        return $this->makeRequest('GET', '/nodes');
    }
}
