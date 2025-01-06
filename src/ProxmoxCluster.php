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
        return $this->makeRequest('GET', 'nodes');
    }

    /**
     * Get cluster status
     * @throws \Exception
     */
    public function getClusterStatus()
    {
        return $this->makeRequest('GET', 'cluster/status');
    }

    /**
     * Get cluster resources
     * @throws \Exception
     */
    public function getClusterResources()
    {
        return $this->makeRequest('GET', 'cluster/resources');
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
        return $this->makeRequest('GET', 'cluster/backup');
    }

    /**
     * Create backup schedule
     * @throws \Exception
     */
    public function createBackupSchedule($data)
    {
        return $this->makeRequest('POST', 'cluster/backup', $data);
    }

    /**
     * Read vzdump backup job definition.
     * @param string $id The job ID.
     * @throws \Exception
     */
    public function backupId($id)
    {
        return $this->makeRequest('GET', "cluster/backup/$id");
    }

    /**
     * Update vzdump backup job definition.
     * @param string $id The job ID.
     * @param array $data
     * @throws \Exception
     */
    public function updateBackup($id, array $data)
    {
        return $this->makeRequest('PUT', "cluster/backup/$id", $data);
    }

    /**
     * Delete vzdump backup job definition.
     * @param string $id The job ID.
     * @throws \Exception
     */
    public function deleteBackup($id)
    {
        return $this->makeRequest('DELETE', "cluster/backup/$id");
    }

    /**
     * Read cluster config.
     * @throws \Exception
     */
    public function config()
    {
        return $this->makeRequest('GET', "cluster/config");
    }

    /**
     * node list.
     * GET cluster config nodes
     * @throws \Exception
     */
    public function listConfigNodes()
    {
        return $this->makeRequest('GET', "cluster/config/nodes");
    }

    /**
     * Get corosync totem protocol settings.
     * GET /api2/json/cluster/config/totem
     * @throws \Exception
     */
    public function configTotem()
    {
        return $this->makeRequest('GET', "cluster/config/totem");
    }

    /**
     *  * Directory index.
     * Get cluster firewall settings
     * @throws \Exception
     */
    public function getFirewallSettings()
    {
        return $this->makeRequest('GET', 'cluster/firewall');
    }

    /**
     * Update cluster firewall settings
     * @throws \Exception
     */
    public function updateFirewallSettings($data)
    {
        return $this->makeRequest('PUT', 'cluster/firewall', $data);
    }

    /**
     * List aliases
     * @throws \Exception
     */
    public function firewallListAliases()
    {
        return $this->makeRequest('GET', 'cluster/firewall/aliases');
    }

    /**
     * Create IP or Network Alias.
     * @param array $data
     * @throws \Exception
     */
    public function createFirewallAliase(array $data)
    {
        return $this->makeRequest('POST', 'cluster/firewall/aliases');
    }

    /**
     * Read alias.
     * @param string $name Alias name.
     * @throws \Exception
     */
    public function getFirewallAliasesName($name)
    {
        return $this->makeRequest('GET', "cluster/firewall/aliases/$name");
    }

    /**
     * Update IP or Network alias.
     * @param string $name Alias name.
     * @param array $data
     * @throws \Exception
     */
    public function updateFirewallAliase($name, array $data)
    {
        return $this->makeRequest('PUT', "cluster/firewall/aliases/$name", $data);
    }

    /**
     * Update IP or Network alias.
     * @param string $name Alias name.
     * @throws \Exception
     */
    public function removeFirewallAliase($name)
    {
        return $this->makeRequest('DELETE', "cluster/firewall/aliases/$name");
    }

    /**
     * List security groups.
     * @throws \Exception
     */
    public function firewallListGroups()
    {
        return $this->makeRequest('GET', "cluster/firewall/groups");
    }

    /**
     * Create new security group.
     * @param array $data
     * @throws \Exception
     */
    public function createFirewallGroup(array $data)
    {
        return $this->makeRequest('POST', "cluster/firewall/groups");
    }

    /**
     * List rules
     * @param string $group Security Group name.
     * @throws \Exception
     */
    public function firewallGroupsGroup($group)
    {
        return $this->makeRequest('GET', "cluster/firewall/groups/$group");
    }

    /**
     * Create new rule.
     * @param string $group Security Group name.
     * @param array $data
     * @throws \Exception
     */
    public function createRuleFirewallGroup($group, array $data)
    {
        return $this->makeRequest('POST', "cluster/firewall/groups/$group", $data);
    }

    /**
     * Delete security group.
     * @param string $group Security Group name.
     * @throws \Exception
     */
    public function removeFirewallGroup($group)
    {
        return $this->makeRequest("DELETE","cluster/firewall/groups/$group");
    }

    /**
     * Get single rule data.
     * @param string   $group    Security Group name.
     * @param integer  $pos      Update rule at position <pos>.
     */
    public function firewallGroupsGroupPos($group, $pos)
    {
        return $this->makeRequest("GET","cluster/firewall/groups/$group/$pos");
    }

    /**
     * Modify rule data
     * @param string $group Security Group name.
     * @param integer $pos Update rule at position <pos>.
     * @param array $data
     * @throws \Exception
     */
    public function setFirewallGroupPos($group, $pos, array $data)
    {
        return $this->makeRequest("PUT","cluster/firewall/groups/$group/$pos", $data);
    }

    /**
     * Delete rule.
     * @param string $group Security Group name.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function removeFirewallGroupPos($group, $pos)
    {
        return $this->makeRequest("DELETE","cluster/firewall/groups/$group/$pos");
    }

    /**
     * List IPSets
     * @throws \Exception
     */
    public function firewallListIpset()
    {
        return $this->makeRequest("GET",'cluster/firewall/ipset');
    }

    /**
     * Create new IPSet
     * @param array $data
     * @throws \Exception
     */
    public function createFirewallIpset(array $data)
    {
        return $this->makeRequest("POST",'cluster/firewall/ipset', $data);
    }

    /**
     * List IPSet content
     * @param string $name IP set name.
     * @throws \Exception
     */
    public function firewallIpsetName($name)
    {
        return $this->makeRequest("GET","cluster/firewall/ipset/$name");
    }

    /**
     * Add IP or Network to IPSet.
     * @param string $name IP set name.
     * @param array $data
     * @throws \Exception
     */
    public function addFirewallIpsetName($name, array $data)
    {
        return $this->makeRequest("POST","cluster/firewall/ipset/$name", $data);
    }

    /**
     * Delete IPSet
     * @param string $name IP set name.
     * @throws \Exception
     */
    public function deleteFirewallIpsetName($name)
    {
        return $this->makeRequest("DELETE","cluster/firewall/ipset/$name");
    }

    /**
     * List rules.
     * @throws \Exception
     */
    public function firewallListRules()
    {
        return $this->makeRequest("GET","cluster/firewall/rules");
    }

    /**
     * Create new rule.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createFirewallRules(array $data)
    {
        return $this->makeRequest("POST","cluster/firewall/rules", $data);
    }

    /**
     * Get single rule data.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function firewallRulesPos($pos)
    {
        return $this->makeRequest("GET","cluster/firewall/rules/$pos");
    }

    /**
     * Delete rule.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function deleteFirewallRulesPos($pos)
    {
        return $this->makeRequest("DELETE","cluster/firewall/rules/$pos");
    }

    /**
     * List available macros
     * @throws \Exception
     */
    public function firewallListMacros()
    {
        return $this->makeRequest("GET","cluster/firewall/macros");
    }

    /**
     * Get Firewall options.
     * @throws \Exception
     */
    public function firewallListOptions()
    {
        return $this->makeRequest("GET","cluster/firewall/options");
    }

    /**
     * Set Firewall options.
     * @param array $data
     * @throws \Exception
     */
    public function setFirewallOptions(array $data)
    {
        return $this->makeRequest("PUT","cluster/firewall/options", $data);
    }

    /**
     * Lists possible IPSet/Alias reference which are allowed in source/dest properties.
     * @throws \Exception
     */
    public function firewallListRefs()
    {
        return $this->makeRequest("GET","cluster/firewall/refs");
    }

    /**
     * Get cluster HA resources
     * @throws \Exception
     */
    public function getHAResources()
    {
        return $this->makeRequest('GET', 'cluster/ha/resources');
    }

    /**
     * Read ha group configuration.
     * @param string $group The HA group identifier.
     * @throws \Exception
     */
    public function HaGroups($group)
    {
        return $this->makeRequest('GET', "cluster/ha/groups/$group");
    }

    /**
     * List HA resources.
     * @throws \Exception
     */
    public function HaResources()
    {
        return $this->makeRequest('GET', "cluster/ha/resources");
    }

    /**
     * List HA resources.
     * @throws \Exception
     */
    public function Replication()
    {
        return $this->makeRequest('GET', "cluster/replication");
    }

    /**
     * Create a new replication job
     * @param array $data
     * @throws \Exception
     */
    public function createReplication(array $data)
    {
        return $this->makeRequest('POST', "cluster/replication", $data);
    }

    /**
     * Read replication job configuration.
     * @param string $id Replication Job ID. The ID is composed of a Guest ID and a job number, separated by a hyphen, i.e. '<GUEST>-<JOBNUM>'.
     * @throws \Exception
     */
    public function replicationId($id)
    {
        return $this->makeRequest('GET', "cluster/replication/$id");
    }

    /**
     * Update replication job configuration.
     * @param string $id Replication Job ID. The ID is composed of a Guest ID and a job number, separated by a hyphen, i.e. '<GUEST>-<JOBNUM>'.
     * @param array $data
     * @throws \Exception
     */
    public function updateReplication($id, $data = array())
    {
        return $this->makeRequest('PUT', "cluster/replication/$id", $data);
    }

    /**
     * Mark replication job for removal.
     * @param string $id Replication Job ID. The ID is composed of a Guest ID and a job number, separated by a hyphen, i.e. '<GUEST>-<JOBNUM>'.
     * @throws \Exception
     */
    public function deleteReplication($id)
    {
        return $this->makeRequest('DELETE', "cluster/replication/$id");
    }

    /**
     * Read cluster log
     * @param integer  $max     Maximum number of entries.
     */
    public function log($max = null)
    {
        $optional['max'] = !empty($max) ? $max : null;
        return $this->makeRequest('GET', "cluster/log", $optional);
    }

    /**
     * Get next free VMID. If you pass an VMID it will raise an error if the ID is already used.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws \Exception
     */
    public function nextVmid($vmid = null)
    {
        $optional['vmid'] = !empty($vmid) ? $vmid : null;

        return $this->makeRequest('GET', "cluster/nextid", $optional);
    }

    /**
     * Get datacenter options.
     * @throws \Exception
     */
    public function Options()
    {
        return $this->makeRequest('GET', "cluster/options");
    }

    /**
     * Set datacenter options.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function setOptions(array $data)
    {
        return $this->makeRequest('PUT', "cluster/options", $data);
    }

    /**
     * Resources index (cluster wide).
     * @param enum     $type    vm | storage | node
     */
    public function Resources($type = null)
    {
        $optional['type'] = !empty($type) ? $type : null;
        return $this->makeRequest('GET', "cluster/resources", $optional);
    }

    /**
     * Get cluster status informations.
     * @throws \Exception
     */
    public function status()
    {
        return $this->makeRequest('GET', "cluster/status");
    }
    /**
     * List recent tasks (cluster wide).
     */
    public function tasks()
    {
        return $this->makeRequest('GET', "cluster/tasks");
    }

    /**
     * Create HA resource
     * @throws \Exception
     */
    public function createHAResource($data)
    {
        return $this->makeRequest('POST', 'cluster/ha/resources', $data);
    }
}
