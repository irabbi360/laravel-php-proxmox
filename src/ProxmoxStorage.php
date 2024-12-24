<?php

namespace Irabbi360\Proxmox;

class ProxmoxStorage extends Proxmox
{
    /**
     * @throws \Exception
     */
    public function storage()
    {
        return $this->makeRequest('GET','/version');
    }
}
