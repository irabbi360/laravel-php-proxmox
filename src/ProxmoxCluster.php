<?php

namespace Irabbi360\Proxmox;

use Irabbi360\Proxmox\Helpers\ResponseHelper;

class ProxmoxCluster extends Proxmox
{
    /**
     * @throws \Exception
     */
    public function cluster()
    {
        $response = $this->makeRequest('GET', 'nodes');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster fail.');
        }

        return ResponseHelper::generate(true,'Cluster list', $response['data']);
    }

    /**
     * Get cluster status
     * @throws \Exception
     */
    public function getClusterStatus()
    {
        $response = $this->makeRequest('GET', 'cluster/status');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster status fail.');
        }

        return ResponseHelper::generate(true,'Cluster status', $response['data']);
    }

    /**
     * Get cluster resources
     * @throws \Exception
     */
    public function getClusterResources()
    {
        $response = $this->makeRequest('GET', 'cluster/resources');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster resources fail.');
        }

        return ResponseHelper::generate(true,'Cluster resources', $response['data']);
    }

    /**
     * Get cluster tasks
     * @throws \Exception
     */
    public function getClusterTasks()
    {
        $response = $this->makeRequest('GET', '/cluster/tasks');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster tasks fail.');
        }

        return ResponseHelper::generate(true,'Cluster tasks', $response['data']);
    }

    /**
     * Get cluster log
     * @throws \Exception
     */
    public function getClusterLog()
    {
        $response = $this->makeRequest('GET', '/cluster/log');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster log fail.');
        }

        return ResponseHelper::generate(true,'Cluster log', $response['data']);
    }

    /**
     * Get cluster backup schedule
     * @throws \Exception
     */
    public function getBackupSchedule()
    {
        $response = $this->makeRequest('GET', 'cluster/backup');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster backup schedule fail.');
        }

        return ResponseHelper::generate(true,'Cluster backup schedule', $response['data']);
    }

    /**
     * Create backup schedule
     * @throws \Exception
     */
    public function createBackupSchedule($data)
    {
        $response = $this->makeRequest('POST', 'cluster/backup', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster Create backup schedule fail.');
        }

        return ResponseHelper::generate(true,'Cluster Create backup schedule', $response['data']);
    }

    /**
     * Read vzdump backup job definition.
     * @param string $id The job ID.
     * @throws \Exception
     */
    public function backupId($id)
    {
        $response = $this->makeRequest('GET', "cluster/backup/$id");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster backup job fail.');
        }

        return ResponseHelper::generate(true,'Cluster backup job', $response['data']);
    }

    /**
     * Update vzdump backup job definition.
     * @param string $id The job ID.
     * @param array $data
     * @throws \Exception
     */
    public function updateBackup($id, array $data)
    {
        $response = $this->makeRequest('PUT', "cluster/backup/$id", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster backup job create fail.');
        }

        return ResponseHelper::generate(true,'Cluster backup job created', $response['data']);
    }

    /**
     * Delete vzdump backup job definition.
     * @param string $id The job ID.
     * @throws \Exception
     */
    public function deleteBackup($id)
    {
        $response = $this->makeRequest('DELETE', "cluster/backup/$id");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster backup job delete fail.');
        }

        return ResponseHelper::generate(true,'Cluster backup job deleted', $response['data']);
    }

    /**
     * Read cluster config.
     * @throws \Exception
     */
    public function config()
    {
        $response = $this->makeRequest('GET', "cluster/config");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster config fail.');
        }

        return ResponseHelper::generate(true,'Cluster config', $response['data']);
    }

    /**
     * node list.
     * GET cluster config nodes
     * @throws \Exception
     */
    public function listConfigNodes()
    {
        $response = $this->makeRequest('GET', "cluster/config/nodes");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster node config fail.');
        }

        return ResponseHelper::generate(true,'Cluster node config', $response['data']);
    }

    /**
     * Get corosync totem protocol settings.
     * GET /api2/json/cluster/config/totem
     * @throws \Exception
     */
    public function configTotem()
    {
        $response = $this->makeRequest('GET', "cluster/config/totem");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster totem protocol settings fail.');
        }

        return ResponseHelper::generate(true,'Cluster totem protocol settings', $response['data']);
    }

    /**
     *  * Directory index.
     * Get cluster firewall settings
     * @throws \Exception
     */
    public function getFirewallSettings()
    {
        $response = $this->makeRequest('GET', 'cluster/firewall');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall', $response['data']);
    }

    /**
     * Update cluster firewall settings
     * @throws \Exception
     */
    public function updateFirewallSettings($data)
    {
        $response = $this->makeRequest('PUT', 'cluster/firewall', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall update fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall updated successfully', $response['data']);
    }

    /**
     * List aliases
     * @throws \Exception
     */
    public function firewallListAliases()
    {
        $response = $this->makeRequest('GET', 'cluster/firewall/aliases');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall aliases fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall aliases', $response['data']);
    }

    /**
     * Create IP or Network Alias.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createFirewallAliase(array $data)
    {
        $response = $this->makeRequest('POST', 'cluster/firewall/aliases');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall Create fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall created', $response['data']);
    }

    /**
     * Read alias.
     * @param string $name Alias name.
     * @throws \Exception
     */
    public function getFirewallAliasesName($name)
    {
        $response = $this->makeRequest('GET', "cluster/firewall/aliases/$name");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall alias fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall alias', $response['data']);
    }

    /**
     * Update IP or Network alias.
     * @param string $name Alias name.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateFirewallAliase($name, array $data)
    {
        $response = $this->makeRequest('PUT', "cluster/firewall/aliases/$name", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall alias update fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall alias updated successfully', $response['data']);
    }

    /**
     * Update IP or Network alias.
     * @param string $name Alias name.
     * @throws \Exception
     */
    public function removeFirewallAliase($name)
    {
        $response = $this->makeRequest('DELETE', "cluster/firewall/aliases/$name");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall alias delete fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall alias deleted successfully', $response['data']);
    }

    /**
     * List security groups.
     * @throws \Exception
     */
    public function firewallListGroups()
    {
        $response = $this->makeRequest('GET', "cluster/firewall/groups");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall groups fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall groups', $response['data']);
    }

    /**
     * Create new security group.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createFirewallGroup(array $data)
    {
        $response = $this->makeRequest('POST', "cluster/firewall/groups");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall security group fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall security group', $response['data']);
    }

    /**
     * List rules
     * @param string $group Security Group name.
     * @throws \Exception
     */
    public function firewallGroupsGroup($group)
    {
        $response = $this->makeRequest('GET', "cluster/firewall/groups/$group");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall security group fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall security group', $response['data']);
    }

    /**
     * Create new rule.
     * @param string $group Security Group name.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createRuleFirewallGroup($group, array $data)
    {
        $response = $this->makeRequest('POST', "cluster/firewall/groups/$group", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall create fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall created', $response['data']);
    }

    /**
     * Delete security group.
     * @param string $group Security Group name.
     * @throws \Exception
     */
    public function removeFirewallGroup($group)
    {
        $response = $this->makeRequest("DELETE","cluster/firewall/groups/$group");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall remove fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall removed successfully');
    }

    /**
     * Get single rule data.
     * @param string $group Security Group name.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function firewallGroupsGroupPos($group, $pos)
    {
        $response = $this->makeRequest("GET","cluster/firewall/groups/$group/$pos");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall group position fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall group position details', $response['data']);
    }

    /**
     * Modify rule data
     * @param string $group Security Group name.
     * @param integer $pos Update rule at position <pos>.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function setFirewallGroupPos($group, $pos, array $data)
    {
        $response = $this->makeRequest("PUT","cluster/firewall/groups/$group/$pos", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall group position update fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall group position updated', $response['data']);
    }

    /**
     * Delete rule.
     * @param string $group Security Group name.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function removeFirewallGroupPos($group, $pos)
    {
        $response = $this->makeRequest("DELETE","cluster/firewall/groups/$group/$pos");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall group position delete fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall group position deleted successfully');
    }

    /**
     * List IPSets
     * @throws \Exception
     */
    public function firewallListIpset()
    {
        $response = $this->makeRequest("GET",'cluster/firewall/ipset');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall ips fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall ips', $response['data']);
    }

    /**
     * Create new IPSet
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createFirewallIpset(array $data)
    {
        $response = $this->makeRequest("POST",'cluster/firewall/ipset', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall ip create fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall ip created successfully', $response['data']);
    }

    /**
     * List IPSet content
     * @param string $name IP set name.
     * @throws \Exception
     */
    public function firewallIpsetName($name)
    {
        $response = $this->makeRequest("GET","cluster/firewall/ipset/$name");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall ip details fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall ip details', $response['data']);
    }

    /**
     * Add IP or Network to IPSet.
     * @param string $name IP set name.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function addFirewallIpsetName($name, array $data)
    {
        $response = $this->makeRequest("POST","cluster/firewall/ipset/$name", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall ip add fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall ip added successfully', $response['data']);
    }

    /**
     * Delete IPSet
     * @param string $name IP set name.
     * @throws \Exception
     */
    public function deleteFirewallIpsetName($name)
    {
        $response = $this->makeRequest("DELETE","cluster/firewall/ipset/$name");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall ip delete fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall ip deleted successfully');
    }

    /**
     * List rules.
     * @throws \Exception
     */
    public function firewallListRules()
    {
        $response = $this->makeRequest("GET","cluster/firewall/rules");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall list rules fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall list rules', $response['data']);
    }

    /**
     * Create new rule.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createFirewallRules(array $data)
    {
        $response = $this->makeRequest("POST","cluster/firewall/rules", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall create rule fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall created rule successfully', $response['data']);
    }

    /**
     * Get single rule data.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function firewallRulesPos($pos)
    {
        $response = $this->makeRequest("GET","cluster/firewall/rules/$pos");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall Get single rule data fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall Get single rule data', $response['data']);
    }

    /**
     * Delete rule.
     * @param integer $pos Update rule at position <pos>.
     * @throws \Exception
     */
    public function deleteFirewallRulesPos($pos)
    {
        $response = $this->makeRequest("DELETE","cluster/firewall/rules/$pos");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall rule delete fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall rule deleted successfully');
    }

    /**
     * List available macros
     * @throws \Exception
     */
    public function firewallListMacros()
    {
        $response = $this->makeRequest("GET","cluster/firewall/macros");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall macros fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall macros', $response['data']);
    }

    /**
     * Get Firewall options.
     * @throws \Exception
     */
    public function firewallListOptions()
    {
        $response = $this->makeRequest("GET","cluster/firewall/options");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall options fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall options', $response['data']);
    }

    /**
     * Set Firewall options.
     * @param array $data
     * @throws \Exception
     */
    public function setFirewallOptions(array $data)
    {
        $response = $this->makeRequest("PUT","cluster/firewall/options", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall options update fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall options updated', $response['data']);
    }

    /**
     * Lists possible IPSet/Alias reference which are allowed in source/dest properties.
     * @throws \Exception
     */
    public function firewallListRefs()
    {
        $response = $this->makeRequest("GET","cluster/firewall/refs");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster firewall refs fail.');
        }

        return ResponseHelper::generate(true,'Cluster firewall refs', $response['data']);
    }

    /**
     * Get cluster HA resources
     * @throws \Exception
     */
    public function getHAResources()
    {
        $response = $this->makeRequest('GET', 'cluster/ha/resources');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster HA resources fail.');
        }

        return ResponseHelper::generate(true,'Cluster HA resources', $response['data']);
    }

    /**
     * Read ha group configuration.
     * @param string $group The HA group identifier.
     * @throws \Exception
     */
    public function HaGroups($group)
    {
        $response = $this->makeRequest('GET', "cluster/ha/groups/$group");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster HA group fail.');
        }

        return ResponseHelper::generate(true,'Cluster HA group', $response['data']);
    }

    /**
     * List replication.
     * @throws \Exception
     */
    public function replication()
    {
        $response = $this->makeRequest('GET', "cluster/replication");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster replication fail.');
        }

        return ResponseHelper::generate(true,'Cluster replication', $response['data']);
    }

    /**
     * Create a new replication job
     * @param array $data
     * @throws \Exception
     */
    public function createReplication(array $data)
    {
        $response = $this->makeRequest('POST', "cluster/replication", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster replication create fail.');
        }

        return ResponseHelper::generate(true,'Cluster replication created successfully', $response['data']);
    }

    /**
     * Read replication job configuration.
     * @param string $id Replication Job ID. The ID is composed of a Guest ID and a job number, separated by a hyphen, i.e. '<GUEST>-<JOBNUM>'.
     * @throws \Exception
     */
    public function replicationId($id)
    {
        $response = $this->makeRequest('GET', "cluster/replication/$id");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster replication details fail.');
        }

        return ResponseHelper::generate(true,'Cluster replication details', $response['data']);
    }

    /**
     * Update replication job configuration.
     * @param string $id Replication Job ID. The ID is composed of a Guest ID and a job number, separated by a hyphen, i.e. '<GUEST>-<JOBNUM>'.
     * @param array $data
     * @throws \Exception
     */
    public function updateReplication($id, array $data)
    {
        $response = $this->makeRequest('PUT', "cluster/replication/$id", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster replication update fail.');
        }

        return ResponseHelper::generate(true,'Cluster replication updated successfully', $response['data']);
    }

    /**
     * Mark replication job for removal.
     * @param string $id Replication Job ID. The ID is composed of a Guest ID and a job number, separated by a hyphen, i.e. '<GUEST>-<JOBNUM>'.
     * @throws \Exception
     */
    public function deleteReplication($id)
    {
        $response = $this->makeRequest('DELETE', "cluster/replication/$id");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster replication delete fail.');
        }

        return ResponseHelper::generate(true,'Cluster replication deleted successfully');
    }

    /**
     * Read cluster log
     * @param integer  $max     Maximum number of entries.
     */
    public function log($max = null)
    {
        $optional['max'] = !empty($max) ? $max : null;
        $response = $this->makeRequest('GET', "cluster/log", $optional);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster log fail.');
        }

        return ResponseHelper::generate(true,'Cluster log', $response['data']);
    }

    /**
     * Get next free VMID. If you pass an VMID it will raise an error if the ID is already used.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws \Exception
     */
    public function nextVmid($vmid = null)
    {
        $optional['vmid'] = !empty($vmid) ? $vmid : null;

        $response = $this->makeRequest('GET', "cluster/nextid", $optional);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster next Vmid fail.');
        }

        return ResponseHelper::generate(true,'Cluster next Vmid', $response['data']);
    }

    /**
     * Get datacenter options.
     * @throws \Exception
     */
    public function options()
    {
        $response = $this->makeRequest('GET', "cluster/options");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster datacenter options fail.');
        }

        return ResponseHelper::generate(true,'Cluster datacenter options', $response['data']);
    }

    /**
     * Set datacenter options.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function setOptions(array $data)
    {
        $response = $this->makeRequest('PUT', "cluster/options", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster set datacenter options fail.');
        }

        return ResponseHelper::generate(true,'Cluster set datacenter options', $response['data']);
    }

    /**
     * Resources index (cluster wide).
     * @param enum     $type    vm | storage | node
     */
    public function Resources($type = null)
    {
        $optional['type'] = !empty($type) ? $type : null;
        $response = $this->makeRequest('GET', "cluster/resources", $optional);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster resources fail.');
        }

        return ResponseHelper::generate(true,'Cluster resources', $response['data']);
    }

    /**
     * Get cluster status informations.
     * @throws \Exception
     */
    public function status()
    {
        $response = $this->makeRequest('GET', "cluster/status");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster status fail.');
        }

        return ResponseHelper::generate(true,'Cluster status', $response['data']);
    }
    /**
     * List recent tasks (cluster wide).
     */
    public function tasks()
    {
        $response = $this->makeRequest('GET', "cluster/tasks");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster tasks fail.');
        }

        return ResponseHelper::generate(true,'Cluster tasks', $response['data']);
    }

    /**
     * Create HA resource
     * @throws \Exception
     */
    public function createHAResource($data)
    {
        $response = $this->makeRequest('POST', 'cluster/ha/resources', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Cluster Create HA resource fail.');
        }

        return ResponseHelper::generate(true,'Cluster Create HA resource successfully', $response['data']);
    }
}
