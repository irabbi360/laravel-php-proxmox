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

    /**
     * Get cluster status
     * @throws \Exception
     */
    public function getClusterStatus()
    {
        return $this->makeRequest('GET', '/cluster/status');
    }

    /**
     * Get cluster resources
     * @throws \Exception
     */
    public function getClusterResources()
    {
        return $this->makeRequest('GET', '/cluster/resources');
    }

    /**
     * Get cluster tasks
     * @throws \Exception
     */
    public function getClusterTasks()
    {
        return $this->makeRequest('GET', '/cluster/tasks');
    }

    /**
     * Get cluster log
     * @throws \Exception
     */
    public function getClusterLog()
    {
        return $this->makeRequest('GET', '/cluster/log');
    }

    /**
     * Get cluster backup schedule
     * @throws \Exception
     */
    public function getBackupSchedule()
    {
        return $this->makeRequest('GET', '/cluster/backup');
    }

    /**
     * Create backup schedule
     * @throws \Exception
     */
    public function createBackupSchedule($data)
    {
        return $this->makeRequest('POST', '/cluster/backup', $data);
    }

    /**
     * Get cluster firewall settings
     * @throws \Exception
     */
    public function getFirewallSettings()
    {
        return $this->makeRequest('GET', '/cluster/firewall');
    }

    /**
     * Update cluster firewall settings
     * @throws \Exception
     */
    public function updateFirewallSettings($data)
    {
        return $this->makeRequest('PUT', '/cluster/firewall', $data);
    }

    /**
     * Get cluster HA resources
     * @throws \Exception
     */
    public function getHAResources()
    {
        return $this->makeRequest('GET', '/cluster/ha/resources');
    }

    /**
     * Create HA resource
     * @throws \Exception
     */
    public function createHAResource($data)
    {
        return $this->makeRequest('POST', '/cluster/ha/resources', $data);
    }
}
