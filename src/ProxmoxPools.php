<?php

namespace Irabbi360\Proxmox;

use Irabbi360\Proxmox\Helpers\ResponseHelper;

class ProxmoxPools extends Proxmox
{
    /**
     * Read system log
     * @throws \Exception
     */
    public function pools()
    {
        $response = $this->makeRequest('GET', 'pools');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Pools system log fail.');
        }
        return ResponseHelper::generate(true,'Pools system log.', $response['data']);
    }

    /**
     * pools Id system log
     * @param string $poolid
     * @throws \Exception
     */
    public function poolsId($poolid)
    {
        $response = $this->makeRequest('GET', "pools/$poolid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Pools system log fail.');
        }
        return ResponseHelper::generate(true,'Pools system log.', $response['data']);
    }
    /**
     * Read system log
     * @param string   $poolid
     */
    public function PutPool($poolid, $data = array())
    {
        $response = $this->makeRequest('PUT', "pools/$poolid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Pools system log fail.');
        }
        return ResponseHelper::generate(true,'Pools system log.', $response['data']);
    }
}
