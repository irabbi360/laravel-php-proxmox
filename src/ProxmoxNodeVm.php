<?php

namespace Irabbi360\Proxmox;

use Exception;
use Irabbi360\Proxmox\Helpers\ResponseHelper;

class ProxmoxNodeVm extends Proxmox
{
    /**
     * API version details, including some parts of the global datacenter config.
     *
     * @return array
     * @throws Exception
     */
    public function version(): array
    {
        $response = $this->makeRequest('GET', 'version');

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'version fetch fail!');
        }
        return ResponseHelper::generate(true,'version!', $response['data']);
    }

    /**
     * Get list of nodes
     *
     * @return array
     * @throws Exception
     */
    public function getNodes(): array
    {
        $response = $this->makeRequest('GET', 'nodes');

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'nodes fetch fail!');
        }
        return ResponseHelper::generate(true,'nodes list!', $response['data']);
    }

    /**
     * Directory index for apt (Advanced Package Tool).
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function apt(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/apt");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'node apt fail!');
        }

        return ResponseHelper::generate(true,'node apt', $response['data']);
    }

    /**
     * Directory index for apt (Advanced Package Tool).
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function updateApt(string $node, array $data)
    {
        $response = $this->makeRequest("POST", "nodes/$node/apt/update", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'apt update fail!');
        }

        return ResponseHelper::generate(true,'Apt updated', $response['data']);
    }

    /**
     * Get package changelogs.
     * @param string $node The cluster node name.
     * @param string|null $name Package name.
     * @throws Exception
     */
    public function aptChangelog(string $node, string $name = null)
    {
        $optional['name'] = !empty($name) ? $name : null;
        $response = $this->makeRequest("GET", "nodes/$node/apt/changelog", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Apt changelog fail!');
        }

        return ResponseHelper::generate(true,'Apt changelog', $response['data']);
    }

    /**
     * List available updates.
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function aptUpdate(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/apt/update");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List available updates fail!');
        }

        return ResponseHelper::generate(true,'List available updates', $response['data']);
    }

    /**
     * This is used to resynchronize the package index files from their sources (apt-get update).
     * POST /api2/json/nodes/{node}/apt/update
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createAptUpdate(string $node, array $data)
    {
        $response = $this->makeRequest("POST", "nodes/$node/apt/update", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create update fail!');
        }

        return ResponseHelper::generate(true,'Create updates', $response['data']);
    }

    /**
     * Directory index.
     * GET /api2/json/nodes/{node}/ceph
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function ceph(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/ceph");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'ceph fail!');
        }

        return ResponseHelper::generate(true,'ceph list', $response['data']);
    }

    /**
     * get all set ceph flags
     * GET /api2/json/nodes/{node}/ceph/flags
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephFlags(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/ceph/flags");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'get all set ceph flags fail!');
        }

        return ResponseHelper::generate(true,'get all set ceph flags', $response['data']);
    }

    /**
     * Set a ceph flag
     * POST /api2/json/nodes/{node}/ceph/flags/{flag}
     * @param string $node The cluster node name.
     * @param enum $flag The ceph flag to set/unset
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function setCephFlags(string $node, $flag, array $data)
    {
        $response = $this->makeRequest("POST", "nodes/$node/ceph/flags/$flag", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Set a ceph flag fail!');
        }

        return ResponseHelper::generate(true,'Set a ceph flag', $response['data']);
    }

    /**
     * Unset a ceph flag
     * DELETE /api2/json/nodes/{node}/ceph/flags/{flag}
     * @param string $node The cluster node name.
     * @param enum $flag The ceph flag to set/unset
     * @throws Exception
     */
    public function unsetCephFlags(string $node, $flag)
    {
        $response = $this->makeRequest("DELETE", "nodes/$node/ceph/flags/$flag");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Unset ceph flag fail!');
        }

        return ResponseHelper::generate(true,'Unset ceph flag', $response['data']);
    }

    /**
     * Create Ceph Manager
     * POST /api2/json/nodes/{node}/ceph/mgr
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCephMgr(string $node, array $data)
    {
        $response = $this->makeRequest("POST", "nodes/$node/ceph/mgr", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create Ceph Manager fail!');
        }

        return ResponseHelper::generate(true,'Created Ceph Manager', $response['data']);
    }

    /**
     * Destroy Ceph Manager.
     * DELETE /api2/json/nodes/{node}/ceph/mgr/{id}
     * @param string $node The cluster node name.
     * @param string $id The ID of the manager
     * @throws Exception
     */
    public function destroyCephMgr(string $node, string $id)
    {
        $response = $this->makeRequest("DELETE", "nodes/$node/ceph/mgr/$id");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create Ceph Manager fail!');
        }

        return ResponseHelper::generate(true,'Created Ceph Manager', $response['data']);
    }

    /**
     * Get Ceph monitor list.
     * GET /api2/json/nodes/{node}/ceph/mon
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephMon(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/ceph/mon");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get Ceph monitor list fail!');
        }

        return ResponseHelper::generate(true,'Get Ceph monitor list', $response['data']);
    }

    /**
     * Create Ceph Monitor and Manager
     * POST /api2/json/nodes/{node}/ceph/mon
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCephMon(string $node, array $data)
    {
        $response = $this->makeRequest("POST", "nodes/$node/ceph/mon", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create Ceph Monitor and Manager fail!');
        }

        return ResponseHelper::generate(true,'Create Ceph Monitor and Manager', $response['data']);
    }

    /**
     * Destroy Ceph Monitor and Manager.
     * DELETE /api2/json/nodes/{node}/ceph/mon/{monid}
     * @param string $node The cluster node name.
     * @param string $monid Monitor ID
     * @throws Exception
     */
    public function destroyCephMon(string $node, string $monid)
    {
        $response = $this->makeRequest("DELETE", "nodes/$node/ceph/mgr/$monid");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Destroy Ceph Monitor and Manager fail!');
        }

        return ResponseHelper::generate(true,'Destroyed Ceph Monitor and Manager', $response['data']);
    }

    /**
     * Get Ceph osd list/tree.
     * GET /api2/json/nodes/{node}/ceph/osd
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephOsd(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/ceph/osd");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get Ceph osd list fail!');
        }

        return ResponseHelper::generate(true,'Get Ceph osd list', $response['data']);
    }

    /**
     * Create OSD
     * POST /api2/json/nodes/{node}/ceph/osd
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCephOsd(string $node, array $data)
    {
        $response = $this->makeRequest("POST", "nodes/$node/ceph/osd", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create OSD fail!');
        }

        return ResponseHelper::generate(true,'Created OSD', $response['data']);
    }

    /**
     * Destroy OSD
     * DELETE /api2/json/nodes/{node}/ceph/osd/{osdid}
     * @param string $node The cluster node name.
     * @param string $osdid OSD ID
     * @throws Exception
     */
    public function destroyCephOsd($node, $osdid)
    {
        $response = $this->makeRequest("DELETE", "nodes/$node/ceph/osd/$osdid");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Destroy OSD fail!');
        }

        return ResponseHelper::generate(true,'Destroyed OSD', $response['data']);
    }

    /**
     * ceph osd in
     * POST /api2/json/nodes/{node}/ceph/osd/{osdid}/in
     * @param string $node The cluster node name.
     * @param string $osdid OSD ID
     * @param array $data
     * @throws Exception
     */
    public function cephOsdIn(string $node, string $osdid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/ceph/osd/$osdid/in", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'ceph OSD in fail!');
        }

        return ResponseHelper::generate(true,'ceph OSD in', $response['data']);
    }

    /**
     * ceph osd out
     * POST /api2/json/nodes/{node}/ceph/osd/{osdid}/out
     * @param string $node The cluster node name.
     * @param string $osdid OSD ID
     * @param array $data
     * @throws Exception
     */
    public function cephOsdOut($node, $osdid, $data = array())
    {
        $response = $this->makeRequest("POST","nodes/$node/ceph/osd/$osdid/out", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'ceph OSD out fail!');
        }

        return ResponseHelper::generate(true,'ceph OSD out', $response['data']);
    }

    /**
     * List all pools.
     * GET /api2/json/nodes/{node}/ceph/pools
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function getCephPools(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/ceph/pools");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List all pools!');
        }

        return ResponseHelper::generate(true,'List all pools', $response['data']);
    }

    /**
     * Create POOL
     * POST /api2/json/nodes/{node}/ceph/pools
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCephPool(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/ceph/pools", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create POOL fail!');
        }

        return ResponseHelper::generate(true,'Created POOL', $response['data']);
    }

    /**
     * Destroy POOL
     * DELETE /api2/json/nodes/{node}/ceph/pools
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function destroyCephPool(string $node)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/ceph/pools");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Destroy POOL fail!');
        }

        return ResponseHelper::generate(true,'Destroyed POOL', $response['data']);
    }

    /**
     * Get Ceph configuration.
     * GET /api2/json/nodes/{node}/ceph/config
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephConfig(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/ceph/config");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get Ceph configuration fail!');
        }

        return ResponseHelper::generate(true,'Get Ceph configuration', $response['data']);
    }

    /**
     * Get OSD crush map
     * GET /api2/json/nodes/{node}/ceph/crush
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephCrush(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/ceph/crush");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get OSD crush map fail!');
        }

        return ResponseHelper::generate(true,'Get OSD crush map', $response['data']);
    }

    /**
     * List local disks.
     * GET /api2/json/nodes/{node}/ceph/disks
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephDisks(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/ceph/disks");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List local disks fail!');
        }

        return ResponseHelper::generate(true,'List local disks', $response['data']);
    }

    /**
     * Create initial ceph default configuration and setup symlinks.
     * POST /api2/json/nodes/{node}/ceph/init
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createCephInit(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/ceph/init", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create initial ceph fail!');
        }

        return ResponseHelper::generate(true,'Create initial ceph', $response['data']);
    }

    /**
     * Read ceph log
     * GET /api2/json/nodes/{node}/ceph/log
     * @param string $node The cluster node name.
     * @param integer|null $limit
     * @param integer|null $start
     * @throws Exception
     */
    public function cephLog(string $node, int $limit = null, int $start = null)
    {
        $optional['limit'] = !empty($limit) ? $limit : 50;
        $optional['start'] = !empty($start) ? $start : 0;

        $response = $this->makeRequest("GET","nodes/$node/ceph/log", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read ceph log fail!');
        }

        return ResponseHelper::generate(true,'Read ceph log', $response['data']);
    }

    /**
     * List ceph rules.
     * GET /api2/json/nodes/{node}/ceph/rules
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephRules(string $node)
    {
        $response = $this->makeRequest("GET", "nodes/$node/ceph/rules");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List ceph rules fail!');
        }

        return ResponseHelper::generate(true,'List ceph rules', $response['data']);
    }

    /**
     * Start ceph services.
     * POST /api2/json/nodes/{node}/ceph/start
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function cephStart(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/ceph/start", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Start ceph services fail!');
        }

        return ResponseHelper::generate(true,'Start ceph services', $response['data']);
    }

    /**
     * Stop ceph services.
     * POST /api2/json/nodes/{node}/ceph/stop
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function cephStop(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/ceph/stop", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Stop ceph services fail!');
        }

        return ResponseHelper::generate(true,'Stop ceph services', $response['data']);
    }

    /**
     * Get ceph status.
     * GET /api2/json/nodes/{node}/ceph/status
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function cephStatus(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/ceph/status");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get ceph status fail!');
        }

        return ResponseHelper::generate(true,'Get ceph status', $response['data']);
    }

    /**
     * Node index.
     * GET /api2/json/nodes/{node}/disks
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function getDisks(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/disks");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Disk list fail!');
        }

        return ResponseHelper::generate(true,'Disk list', $response['data']);
    }

    /**
     * Initialize Disk with GPT
     * POST /api2/json/nodes/{node}/disks
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function disk(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/disks", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Initialize Disk with GPT fail!');
        }

        return ResponseHelper::generate(true,'Initialize Disk with GPT', $response['data']);
    }

    /**
     * List local disks.
     * GET /api2/json/nodes/{node}/disks/list
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function disksList(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/disks/list");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List local disks fail!');
        }

        return ResponseHelper::generate(true,'List local disks', $response['data']);
    }

    /**
     * Get SMART Health of a disk.
     * GET /api2/json/nodes/{node}/disks/smart
     * @param string $node The cluster node name.
     * @param string|null $disk Block device name
     * @throws Exception
     */
    public function disksSmart(string $node, string $disk = null)
    {
        $optional['disk'] = !empty($disk) ? $disk : null;

        $response = $this->makeRequest("GET", "nodes/$node/disks/smart", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get SMART Health of a disk fail!');
        }

        return ResponseHelper::generate(true,'Get SMART Health of a disk', $response['data']);
    }

    /**
     * Directory index.
     * GET /api2/json/nodes/{node}/firewall
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function firewall(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/firewall");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Firewall list fail!');
        }

        return ResponseHelper::generate(true,'Firewall list', $response['data']);
    }

    /**
     * List rules.
     * GET /api2/json/nodes/{node}/firewall/rules
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function firewallRules(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/firewall/rules");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Firewall list rules fail!');
        }

        return ResponseHelper::generate(true,'Firewall list rules', $response['data']);
    }

    /**
     * Create new rule
     * POST /api2/json/nodes/{node}/firewall/rules
     * @param string $node The cluster node name.
     * @param array $data
     * @throws Exception
     */
    public function createFirewallRule($node, array $data)
    {
        $response = $this->makeRequest( "POST","nodes/$node/firewall/rules", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create new rule fail!');
        }

        return ResponseHelper::generate(true,'Created new rule', $response['data']);
    }

    /**
     * Get single rule data.
     * GET /api2/json/nodes/{node}/firewall/rules/{pos}
     * @param string $node The cluster node name.
     * @param integer $pos Update rule at position <pos>.
     * @throws Exception
     */
    public function firewallRulesPos(string $node, int $pos)
    {
        $response = $this->makeRequest( "GET","nodes/$node/firewall/rules/$pos");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get single rule data fail!');
        }

        return ResponseHelper::generate(true,'Get single rule data', $response['data']);
    }

    /**
     * Modify rule data.
     * PUT /api2/json/nodes/{node}/firewall/rules/{pos}
     * @param string $node The cluster node name.
     * @param integer $pos Update rule at position <pos>.
     * @param array $data
     * @throws Exception
     */
    public function setFirewallRulePos(string $node, int $pos, array $data)
    {
        $response = $this->makeRequest( "PUT","nodes/$node/firewall/rules/$pos", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Modify rule data fail!');
        }

        return ResponseHelper::generate(true,'Modify rule data', $response['data']);
    }

    /**
     * Delete rule.
     * DELETE /api2/json/nodes/{node}/firewall/rules/{pos}
     * @param string $node The cluster node name.
     * @param integer $pos Update rule at position <pos>.
     * @throws Exception
     */
    public function deleteFirewallRulePos(string $node, int $pos)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/firewall/rules/$pos");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Delete rule fail!');
        }

        return ResponseHelper::generate(true,'Delete rule', $response['data']);
    }

    /**
     * Read firewall log
     * GET /api2/json/nodes/{node}/firewall/rules/log
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function firewallRulesLog(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/firewall/rules/log");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read firewall log fail!');
        }

        return ResponseHelper::generate(true,'Read firewall log', $response['data']);
    }

    /**
     * Get host firewall options.
     * GET /api2/json/nodes/{node}/firewall/rules/options
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function firewallRulesOptions(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/firewall/rules/options");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get host firewall options fail!');
        }

        return ResponseHelper::generate(true,'Get host firewall options', $response['data']);
    }

    /**
     * Set Firewall options.
     * PUT /api2/json/nodes/{node}/firewall/rules/options
     * @param string $node The cluster node name.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function setFirewallRuleOptions(string $node, array $data)
    {
        $response = $this->makeRequest("PUT","cluster/firewall/options", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Set firewall options fail!');
        }

        return ResponseHelper::generate(true,'Set firewall options', $response['data']);
    }

    /**
     * Create new rule
     * from the Proxmox node's public interface to a VM/Container IP:Port.
     * @throws Exception
     */
    public function createQemuFirewallRule(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest('POST', "nodes/$node/qemu/$vmid/firewall/rules", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(true,'Created Firewall', $response['data']);
        }
        return ResponseHelper::generate(false,'Create Firewall fail!');
    }

    /**
     * List of rule.
     * from the Proxmox node's public interface to a VM/Container IP:Port.
     * @throws Exception
     */
    public function listQemuFirewallRule(string $node, int $vmid)
    {
        $response = $this->makeRequest('GET', "nodes/$node/qemu/$vmid/firewall/rules");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List Firewall fail!');
        }

        return ResponseHelper::generate(true,'List Firewall', $response['data']);
    }

    /**
     * Removing of rule.
     * from the Proxmox node's public interface to a VM/Container IP:Port.
     * @throws Exception
     */
    public function removeQemuFirewallRule(string $node, int $vmid, $pos)
    {
        $response = $this->makeRequest('DELETE', "nodes/$node/qemu/$vmid/firewall/rules/$pos");

        if (!isset($response['data'])){
            return ResponseHelper::generate(true,'Removed Firewall', $response['data']);
        }
        return ResponseHelper::generate(false,'Remove Firewall fail!');
    }

    /**
     * LXC container index (per node).
     * GET /api2/json/nodes/{node}/lxc
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function lxc(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'LXC container fail!');
        }

        return ResponseHelper::generate(true,'LXC containers', $response['data']);
    }

    /**
     * Create or restore a container.
     * POST /api2/json/nodes/{node}/lxc
     * @param string $node The cluster node name.
     * @param array $data
     * @throws Exception
     */
    public function createLxc(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create or restore a container fail!');
        }

        return ResponseHelper::generate(true,'Created or restored a container', $response['data']);
    }

    /**
     * Directory index
     * GET /api2/json/nodes/{node}/lxc/{vmid}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcVmid(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Lxc list fail!');
        }

        return ResponseHelper::generate(true,'Lxc list', $response['data']);
    }

    /**
     * Destroy the container (also delete all uses files).
     * DELETE /api2/json/nodes/{node}/lxc/{vmid}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function deleteLxc($node, $vmid)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/lxc/$vmid");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Destroy the container fail!');
        }

        return ResponseHelper::generate(true,'Destroy the container', $response['data']);
    }

    /**
     * Directory index.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/aliases
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFirewall(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Destroy the container fail!');
        }

        return ResponseHelper::generate(true,'Destroy the container', $response['data']);
    }

    /**
     * List aliases
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/aliases
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFirewallAliases(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/aliases");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List aliases fail!');
        }

        return ResponseHelper::generate(true,'List aliases', $response['data']);
    }

    /**
     * Create IP or Network Alias
     * POST /api2/json/nodes/{node}/lxc/{vmid}/firewall/aliases
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function createLxcFirewallAliase(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/firewall/aliases", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create IP or Network Alias fail!');
        }

        return ResponseHelper::generate(true,'Created IP or Network Alias', $response['data']);
    }

    /**
     * Read alias.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/aliases/{name}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name Alias name.
     * @throws Exception
     */
    public function lxcFirewallAliasesName(string $node, int $vmid, string $name)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/aliases/$name");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read alias fail!');
        }

        return ResponseHelper::generate(true,'Read Alias', $response['data']);
    }

    /**
     * Update IP or Network alias
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/firewall/aliases/{name}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name Alias name.
     * @param array $data
     * @throws Exception
     */
    public function updateLxcFirewallAliaseName(string $node, int $vmid, string $name, array $data)
    {
        $response = $this->makeRequest("PUT","nodes/$node/lxc/$vmid/firewall/aliases/$name", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Update IP or Network alias fail!');
        }

        return ResponseHelper::generate(true,'Updated IP or Network alias', $response['data']);
    }

    /**
     * Remove IP or Network alias.
     * DELETE /api2/json/nodes/{node}/lxc/{vmid}/firewall/aliases/{name}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name Alias name.
     * @throws Exception
     */
    public function deleteLxcFirewallAliaseName(string $node, int $vmid, string $name)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/lxc/$vmid/firewall/aliases/$name");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Remove IP or Network alias fail!');
        }

        return ResponseHelper::generate(true,'Removed IP or Network alias', $response['data']);
    }

    /**
     * List IPSets
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFirewallIpset(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/ipset");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List IPSets fail!');
        }

        return ResponseHelper::generate(true,'List IPSets', $response['data']);
    }

    /**
     * Create new IPSet
     * POST /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function createLxcFirewallIpset(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/firewall/ipset", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create new IPSet fail!');
        }

        return ResponseHelper::generate(true,'Create new IPSet', $response['data']);
    }

    /**
     * List IPSet content
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset/{name}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name IP set name.
     * @throws Exception
     */
    public function lxcFirewallIpsetName(string $node, int $vmid, string $name)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/ipset/$name");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List IPSet content fail!');
        }

        return ResponseHelper::generate(true,'List IPSet content', $response['data']);
    }

    /**
     * Add IP or Network to IPSet.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset/{name}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name IP set name.
     * @param array $data
     * @throws Exception
     */
    public function addLxcFirewallIpsetName(string $node, int $vmid, string $name, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/firewall/ipset/$name", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Add IP or Network to IPSet fail!');
        }

        return ResponseHelper::generate(true,'Add IP or Network to IPSet', $response['data']);
    }

    /**
     * Delete IPSet
     * DELETE /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset/{name}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name IP set name.
     * @throws Exception
     */
    public function deleteLxcFirewallIpsetName(string $node, int $vmid, string $name)
    {
        $response = $this->makeRequest("DELETE","/nodes/$node/lxc/$vmid/firewall/ipset/$name");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Delete IPSet fail!');
        }

        return ResponseHelper::generate(true,'Deleted IPSet', $response['data']);
    }

    /**
     * Read IP or Network settings from IPSet.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset/{name}/{cidr}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name IP set name.
     * @param string $cidr Network/IP specification in CIDR format.
     * @throws Exception
     */
    public function lxcFirewallIpsetNameCidr(string $node, int $vmid, string $name, string $cidr)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/ipset/$name/$cidr");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read IP or Network settings from IPSet fail!');
        }

        return ResponseHelper::generate(true,'Read IP or Network settings from IPSet', $response['data']);
    }

    /**
     * Update IP or Network settings
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset/{name}/{cidr}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name IP set name.
     * @param string $cidr Network/IP specification in CIDR format.
     * @param array $data
     * @throws Exception
     */
    public function updateLxcFirewallIpsetNameCidr(string $node, int $vmid, string $name, string $cidr, array $data)
    {
        $response = $this->makeRequest("PUT","nodes/$node/lxc/$vmid/firewall/ipset/$name/$cidr", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Update IP or Network settings fail!');
        }

        return ResponseHelper::generate(true,'Updated IP or Network settings', $response['data']);
    }

    /**
     * Remove IP or Network settings
     * DELETE /api2/json/nodes/{node}/lxc/{vmid}/firewall/ipset/{name}/{cidr}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $name IP set name.
     * @param string $cidr Network/IP specification in CIDR format.
     * @throws Exception
     */
    public function deleteLxcFirewallIpsetNameCidr(string $node, int $vmid, string $name, string $cidr)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/lxc/$vmid/firewall/ipset/$name/$cidr");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Remove IP or Network settings fail!');
        }

        return ResponseHelper::generate(true,'Removed IP or Network settings', $response['data']);
    }

    /**
     * List rules.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/rules
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFirewallRules(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/rules");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List rules fail!');
        }

        return ResponseHelper::generate(true,'List rules', $response['data']);
    }

    /**
     * Create new rule.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/firewall/rules
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function createLxcFirewallRules(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","/nodes/$node/lxc/$vmid/firewall/rules", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create new rule fail!');
        }

        return ResponseHelper::generate(true,'Created new rule', $response['data']);
    }

    /**
     * Get single rule data.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/rules/{pos}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFirewallRulesPos(string $node, int $vmid, $pos)
    {
        $response = $this->makeRequest("POST","/nodes/$node/lxc/$vmid/firewall/rules/$pos");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get single rule data fail!');
        }

        return ResponseHelper::generate(true,'Get single rule data', $response['data']);
    }

    /**
     * Modify rule data.
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/firewall/rules/{pos}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function setLxcFirewallRulesPos(string $node, int $vmid, $pos, array $data)
    {
        $response = $this->makeRequest("PUT","nodes/$node/lxc/$vmid/firewall/rules/$pos", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Modify rule data fail!');
        }

        return ResponseHelper::generate(true,'Modify rule data', $response['data']);
    }

    /**
     * Delete rule.
     * DELETE /api2/json/nodes/{node}/lxc/{vmid}/firewall/rules/{pos}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function deleteLxcFirewallRulesPos(string $node, int $vmid, $pos)
    {
        $response = $this->makeRequest("DELETE","/nodes/$node/lxc/$vmid/firewall/rules/$pos");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Delete rule fail!');
        }

        return ResponseHelper::generate(true,'Deleted rule', $response['data']);
    }

    /**
     * Read firewall log
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/log
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param integer|null $limit
     * @param integer|null $start
     * @throws Exception
     */
    public function lxcFirewallLog(string $node, int $vmid, int $limit = null, int $start = null)
    {
        $optional['limit'] = !empty($limit) ? $limit : 50;
        $optional['start'] = !empty($start) ? $start : 0;

        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/log", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read firewall log fail!');
        }

        return ResponseHelper::generate(true,'Read firewall log', $response['data']);
    }

    /**
     * Get VM firewall options.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/firewall/options
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFirewallOptions(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/firewall/options");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get VM firewall options fail!');
        }

        return ResponseHelper::generate(true,'Get VM firewall options', $response['data']);
    }

    /**
     * Set Firewall options.
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/firewall/options
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function setLxcFirewallOptions($node, $vmid, $data = array())
    {
        $response = $this->makeRequest("PUT","/nodes/$node/lxc/$vmid/firewall/options", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Set VM firewall options fail!');
        }

        return ResponseHelper::generate(true,'Set VM firewall options', $response['data']);
    }

    /**
     * List all snapshots.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/snapshot
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcSnapshot(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/snapshot");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List all snapshots fail!');
        }

        return ResponseHelper::generate(true,'List all snapshots', $response['data']);
    }

    /**
     * Snapshot a container.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/snapshot
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function createLxcSnapshot(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/snapshot", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Snapshot a container fail!');
        }

        return ResponseHelper::generate(true,'Snapshot a container', $response['data']);
    }

    /**
     * List all snapshots.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/snapshot/{snapname}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $snapname The name of the snapshot.
     * @throws Exception
     */
    public function lxcSnapname(string $node, int $vmid, string $snapname)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/snapshot/$snapname");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List all snapshots fail!');
        }

        return ResponseHelper::generate(true,'List all snapshots', $response['data']);
    }

    /**
     * Delete a LXC snapshot.
     * DELETE /api2/json/nodes/{node}/lxc/{vmid}/snapshot/{snapname}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $snapname The name of the snapshot.
     * @throws Exception
     */
    public function deleteLxcSnapshot(string $node, int $vmid, string $snapname)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/lxc/$vmid/snapshot/$snapname");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'List all snapshots fail!');
        }

        return ResponseHelper::generate(true,'List all snapshots', $response['data']);
    }

    /**
     * Get snapshot configuration
     * GET /api2/json/nodes/{node}/lxc/{vmid}/snapshot/{snapname}/config
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $snapname The name of the snapshot.
     * @throws Exception
     */
    public function lxcSnapnameConfig(string $node, int $vmid, string $snapname)
    {
        $response = $this->makeRequest("GET","/nodes/$node/lxc/$vmid/snapshot/$snapname/config");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get snapshot configuration fail!');
        }

        return ResponseHelper::generate(true,'Get snapshot configuration', $response['data']);
    }

    /**
     * Update snapshot metadata.
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/snapshot/{snapname}/config
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $snapname The name of the snapshot.
     * @param array $data
     * @throws Exception
     */
    public function lxcSnapshotConfig(string $node, int $vmid, string $snapname, array $data)
    {
        $response = $this->makeRequest("PUT","/nodes/$node/lxc/$vmid/snapshot/$snapname/config", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Update snapshot metadata fail!');
        }

        return ResponseHelper::generate(true,'Update snapshot metadata', $response['data']);
    }

    /**
     * Rollback LXC state to specified snapshot.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/snapshot/{snapname}/rollback
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string $snapname The name of the snapshot.
     * @param array $data
     * @throws Exception
     */
    public function lxcSnapshotRollback(string $node, int $vmid, string $snapname, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/snapshot/$snapname/rollback", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Update snapshot metadata fail!');
        }

        return ResponseHelper::generate(true,'Update snapshot metadata', $response['data']);
    }

    /**
     * LXC Status
     * GET /api2/json/nodes/{node}/lxc/{vmid}/status
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcStatus(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/status");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'LXC status fail!');
        }

        return ResponseHelper::generate(true,'LXC status', $response['data']);
    }

    /**
     * Get virtual machine status.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/status/current
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcCurrent(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/status/current");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get virtual machine status fail!');
        }

        return ResponseHelper::generate(true,'Get virtual machine status', $response['data']);
    }

    /**
     * Resume the container.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/status/resume
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcResume(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/status/resume", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Resume the container fail!');
        }

        return ResponseHelper::generate(true,'Resume the container', $response['data']);
    }

    /**
     * Shutdown the container. This will trigger a clean shutdown of the container, see lxc-stop(1) for details.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/status/shutdown
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcShutdown(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","/nodes/$node/lxc/$vmid/status/shutdown", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Shutdown the container fail!');
        }

        return ResponseHelper::generate(true,'Shutdown the container', $response['data']);
    }

    /**
     * Start the container.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/status/start
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcStart(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/status/start", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Start the container fail!');
        }

        return ResponseHelper::generate(true,'Start the container', $response['data']);
    }

    /**
     * Stop the container. This will abruptly stop all processes running in the container.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/status/stop
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcStop(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/status/stop", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Stop the container fail!');
        }

        return ResponseHelper::generate(true,'Stop the container', $response['data']);
    }

    /**
     * Suspend the container.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/status/suspend
     * @param string   $node     The cluster node name.
     * @param integer  $vmid     The (unique) ID of the VM.
     * @param array    $data
     */
    public function lxcSuspend($node, $vmid, $data = array())
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/status/suspend", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Suspend the container fail!');
        }

        return ResponseHelper::generate(true,'Suspend the container', $response['data']);
    }

    /**
     * Reboot the container.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/status/reboot
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcReboot(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/status/reboot", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Reboot the container fail!');
        }

        return ResponseHelper::generate(true,'Reboot the container', $response['data']);
    }

    /**
     * Create a container clone/copy
     * POST /api2/json/nodes/{node}/lxc/{vmid}/clone
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcClone(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","/nodes/$node/lxc/$vmid/clone", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create a container clone fail!');
        }

        return ResponseHelper::generate(true,'Create a container clone', $response['data']);
    }

    /**
     * Get container configuration.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/config
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcConfig(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/config");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Get container configuration fail!');
        }

        return ResponseHelper::generate(true,'Get container configuration', $response['data']);
    }

    /**
     * Set container options.
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/config
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function setLxcConfig(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("PUT","nodes/$node/lxc/$vmid/config", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Set container configuration fail!');
        }

        return ResponseHelper::generate(true,'Set container configuration', $response['data']);
    }

    /**
     * Check if feature for virtual machine is available.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/feature
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function lxcFeature(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/feature");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Check if feature for virtual machine is available fail!');
        }

        return ResponseHelper::generate(true,'Check if feature for virtual machine is available', $response['data']);
    }

    /**
     * Migrate the container to another node. Creates a new migration task.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/migrate
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcMigrate(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/migrate", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Migrate the container to another node. Creates a new migration task fail!');
        }

        return ResponseHelper::generate(true,'Migrate the container to another node. Creates a new migration task', $response['data']);
    }

    /**
     * Resize a container mount point.
     * PUT /api2/json/nodes/{node}/lxc/{vmid}/resize
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcResize(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("PUT","/nodes/$node/lxc/$vmid/resize", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Resize a container mount point fail!');
        }

        return ResponseHelper::generate(true,'Resize a container mount point task', $response['data']);
    }

    /**
     * Read VM RRD statistics (returns PNG)
     * GET /api2/json/nodes/{node}/lxc/{vmid}/rrd
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param string|null $ds The list of datasources you want to display.
     * @param enum $timeframe Specify the time frame you are interested in.
     * @throws Exception
     */
    public function lxcRrd(string $node, int $vmid, string $ds = null, $timeframe = null)
    {
        $optional['ds'] = !empty($ds) ? $ds : null;
        $optional['timeframe'] = !empty($timeframe) ? $timeframe : null;

        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/rrd", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read VM RRD statistics fail!');
        }

        return ResponseHelper::generate(true,'Read VM RRD statistics', $response['data']);
    }

    /**
     * Read VM RRD statistics
     * GET /api2/json/nodes/{node}/lxc/{vmid}/rrddata
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param enum $timeframe Specify the time frame you are interested in.
     * @throws Exception
     */
    public function lxcRrddata($node, $vmid, $timeframe = null)
    {
        $optional['timeframe'] = !empty($timeframe) ? $timeframe : null;

        $response = $this->makeRequest("GET","nodes/$node/lxc/$vmid/rrddata", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read VM RRD Data statistics fail!');
        }

        return ResponseHelper::generate(true,'Read VM RRD Data statistics', $response['data']);
    }

    /**
     * Returns a SPICE configuration to connect to the CT.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/spiceproxy
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function lxcSpiceproxy(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","/nodes/$node/lxc/$vmid/spiceproxy", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Read VM RRD Data statistics fail!');
        }

        return ResponseHelper::generate(true,'Read VM RRD Data statistics', $response['data']);
    }

    /**
     * Create a Template.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/template
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function createLxcTemplate(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","/nodes/$node/lxc/$vmid/template", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create a Template fail!');
        }

        return ResponseHelper::generate(true,'Created a Template', $response['data']);
    }

    /**
     * Creates a TCP VNC proxy connections.
     * POST /api2/json/nodes/{node}/lxc/{vmid}/vncproxy
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param array $data
     * @throws Exception
     */
    public function createLxcVncproxy(string $node, int $vmid, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/lxc/$vmid/vncproxy", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Creates a TCP VNC proxy connections fail!');
        }

        return ResponseHelper::generate(true,'Created a TCP VNC proxy connections', $response['data']);
    }

    /**
     * Opens a weksocket for VNC traffic.
     * GET /api2/json/nodes/{node}/lxc/{vmid}/vncwebsocket
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @param integer|null $port Port number returned by previous vncproxy call.
     * @param string|null $vncticket Ticket from previous call to vncproxy.
     * @throws Exception
     */
    public function lxcVncwebsocket(string $node, int $vmid, int $port = null, string $vncticket = null)
    {
        $optional['port'] = !empty($port) ? $port : null;
        $optional['vncticket'] = !empty($vncticket) ? $vncticket : null;

        $response = $this->makeRequest("GET","/nodes/$node/lxc/$vmid/vncwebsocket", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Opens a weksocket for VNC traffic fail!');
        }

        return ResponseHelper::generate(true,'Opens a weksocket for VNC traffic', $response['data']);
    }

    /**
     * get List available networks
     * GET /api2/json/nodes/{node}/network
     * @param string $node The cluster node name.
     * @param enum|null $type Only list specific interface types.
     * @throws Exception
     */
    public function network(string $node, $type = null)
    {
        $optional['type'] = !empty($type) ? $type : null;
        $response = $this->makeRequest("GET","nodes/$node/network", $optional);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'get List available networks fail!');
        }

        return ResponseHelper::generate(true,'get List available networks', $response['data']);
    }

    /**
     * Create network device configuration
     * POST /api2/json/nodes/{node}/network
     * @param string $node The cluster node name.
     * @param array $data
     * @throws Exception
     */
    public function createNetwork(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/network", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create network device configuration fail!');
        }

        return ResponseHelper::generate(true,'Created network device configurations', $response['data']);
    }

    /**
     * Revert network configuration changes.
     * DELETE /api2/json/nodes/{node}/network
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function revertNetwork(string $node)
    {
        $response = $this->makeRequest("DELETE","nodes/$node/network");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Revert network configuration changes fail!');
        }

        return ResponseHelper::generate(true,'Revert network configuration changes', $response['data']);
    }

    /**
     * Network interface name.
     * GET /api2/json/nodes/{node}/network/{iface}
     * @param string $node The cluster node name.
     * @param string $iface
     * @throws Exception
     */
    public function networkIface(string $node, string $iface)
    {
        $response = $this->makeRequest("GET","/nodes/$node/network/$iface");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Network interface name fail!');
        }

        return ResponseHelper::generate(true,'Network interface name', $response['data']);
    }

    /**
     * Update network device configuration
     * PUT /api2/json/nodes/{node}/network/{iface}
     * @param string $node The cluster node name.
     * @param string $iface
     * @param array $data
     * @throws Exception
     */
    public function updateNetworkIface(string $node, string $iface, array $data)
    {
        $response = $this->makeRequest("PUT","/nodes/$node/network/$iface", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Update network device configuration fail!');
        }

        return ResponseHelper::generate(true,'Updated network device configuration', $response['data']);
    }

    /**
     * Delete network device configuration
     * DELETE /api2/json/nodes/{node}/network/{iface}
     * @param string $node The cluster node name.
     * @param string $iface
     * @throws Exception
     */
    public function deleteNetworkIface($node, $iface)
    {
        $response = $this->makeRequest("DELETE","/nodes/$node/network/$iface");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Delete network device configuration fail!');
        }

        return ResponseHelper::generate(true,'Deleted network device configuration', $response['data']);
    }

    /**
     * Virtual machine index (per node).
     * GET /api2/json/nodes/{node}/qemu
     * @param string $node The cluster node name.
     * @throws Exception
     */
    public function qemu(string $node)
    {
        $response = $this->makeRequest("GET","nodes/$node/qemu");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Virtual machine fail!');
        }

        return ResponseHelper::generate(true,'Virtual machine', $response['data']);
    }

    /**
     * Create or restore a virtual machine.
     * POST /api2/json/nodes/{node}/qemu
     * @param string $node The cluster node name.
     * @param array $data
     * @throws Exception
     */
    public function createQemu(string $node, array $data)
    {
        $response = $this->makeRequest("POST","nodes/$node/qemu", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Create or restore a virtual machine fail!');
        }

        return ResponseHelper::generate(true,'Create or restore a virtual machine', $response['data']);
    }

    /**
     * Directory index
     * GET /api2/json/nodes/{node}/qemu/{vmid}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function qemuVmid(string $node, int $vmid)
    {
        $response = $this->makeRequest("GET","nodes/$node/qemu/$vmid");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'VM details fail!');
        }

        return ResponseHelper::generate(true,'VM details', $response['data']);
    }

    /**
     * Get list of VMs on a specific node
     *
     * @param string $node Node name
     * @return array
     * @throws Exception
     */
    public function getVMs(string $node): array
    {
        $response = $this->makeRequest('GET', "nodes/{$node}/qemu");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'node vms fail!');
        }
        return ResponseHelper::generate(true,'node vm list!', $response['data']);
    }

    /**
     * Available OS types in Proxmox
     *
     * @var array
     */
    private const OS_TYPES = [
        'ubuntu' => 'other', // For Ubuntu and other modern Linux distributions
        'centos' => 'l26',   // For CentOS and older Linux distributions
        'windows' => 'win10' // For Windows
    ];

    /**
     * Create a new Virtual Machine
     *
     * @param string $node Node name
     * @param array $params VM configuration parameters
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function createVM(string $node, array $params)
    {
        // Get next available VMID if not provided
        if (!isset($params['vmid'])) {
            $params['vmid'] = $this->getNextVMID();
        }

        // Set default values if not provided
       /* $defaults = [
            'cores' => 2,
            'sockets' => 2,
            'ostype' => 'other',
            'onboot' => 1,
            'scsihw' => 'virtio-scsi-pci',
            'bootdisk' => 'scsi0',
            'agent' => 1, // Enable QEMU Guest Agent
        ];

        $params = array_merge($defaults, $params);*/

        // Required parameters validation
        $required = ['vmid', 'name', 'ostype', 'memory', 'cores'];
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new Exception("Missing required parameter: {$field}");
            }
        }

        $config = ['cpu' => 'x86-64-v2-AES'];

        // Create VM and get response
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu", $params);
        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM create fail!');
        }

        $this->configVM($node, $params['vmid'], $config);

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $params['vmid'],
        ];

        return ResponseHelper::generate(true, 'VM created successfully', $successResponse);
    }

    protected function configVM(string $node, int $vmId, array $params): array
    {
        return $this->makeRequest('PUT', "nodes/{$node}/qemu/{$vmId}/config", $params);
    }

    public function createStorage(array $params): array
    {
        // Validate required parameters
        $required = ['storage', 'type'];
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new \Exception("Missing required parameter: {$field}");
            }
        }

        $response = $this->makeRequest('POST', 'storage', $params);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'storage create fail!');
        }
        return ResponseHelper::generate(true,'storage created!', $response['data']);
    }

    public function checkTaskStatus(string $node, string $upid)
    {
        $status = $this->makeRequest('GET', "nodes/{$node}/tasks/{$upid}/status");

        if ($status['data']['status'] === 'stopped') {
            if ($status['data']['exitstatus'] === 'OK') {
                return $status; // Task completed successfully
            } else {
                return $status;
                throw new Exception("Task failed with exit status: " . $status['data']['exitstatus']);
            }
        }
        return $status;
    }

    public function waitForTaskCompletion(string $node, string $upid)
    {
        do {
            // Poll task status
            $status = $this->makeRequest('GET', "nodes/{$node}/tasks/{$upid}/status");

            if ($status['data']['status'] === 'stopped') {
                if ($status['data']['exitstatus'] === 'OK') {
                    return $status['data']; // Task completed successfully
                } else {
                    throw new Exception("Task failed with exit status: " . $status['data']['exitstatus']);
                }
            }

            // Wait for a short period before polling again
            sleep(2);
        } while (true);
    }

    /**
     * Get VM status
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @return array
     * @throws Exception
     */
    public function getVMStatus(string $node, int $vmid): array
    {
        $response = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'VM status check fail!');
        }
        return ResponseHelper::generate(true,'VM status!', $response['data']);
    }

    /**
     * GET /api2/json/nodes/{node}/qemu/{vmid}
     * @param string $node The cluster node name.
     * @param integer $vmid The (unique) ID of the VM.
     * @throws Exception
     */
    public function vmQuery(string $node, int $vmid)
    {
        return $this->makeRequest("GET", "nodes/$node/qemu/$vmid/status/current");
    }

    /**
     * Start VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function startVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/start");
        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM start fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM Started', $successResponse);
    }

    /**
     * Stop VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function stopVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/stop");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM stop fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];
        return ResponseHelper::generate(true, 'VM stopped', $successResponse);
    }

    /**
     * Reset VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function resetVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/reset");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM reset fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM reset successfully', $successResponse);
    }

    /**
     * Current Status VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function statusVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM status fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM status', $successResponse);
    }

    /**
     * Reboot VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function rebootVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/reboot");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM reboot fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM rebooted', $successResponse);
    }

    /**
     * Shutdown VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function shutdownVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/shutdown");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM shutdown fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM shutdown', $successResponse);
    }

    /**
     * Resume VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function resumeVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/resume");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM resume fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM resumed', $successResponse);
    }

    /**
     * Suspend VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @throws Exception
     */
    public function suspendVM(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/suspend");

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'VM suspend fail!');
        }

        $successResponse = [
            'data' => $response['data'],
            'node' => $node,
            'vmid' => $vmid,
        ];

        return ResponseHelper::generate(true, 'VM suspended', $successResponse);
    }

    /**
     * Rename a Virtual Machine
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param string $newName New name for the VM
     * @throws Exception
     */
    public function renameVM(string $node, int $vmid, string $newName)
    {
        try {
            // First, check if VM exists
            $vmStatus = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

            if (!isset($vmStatus['data'])) {
                return [
                    'success' => false,
                    'message' => "VM {$vmid} not found on node {$node}",
                    'data' => null
                ];
            }

            // Check if VM is running
            if ($vmStatus['data']['status'] === 'running') {
                return ResponseHelper::generate(false, 'Cannot rename running VM. Please stop the VM first.');
            }

            // Set new name
            $result = $this->setVMConfig($node, $vmid, [
                'name' => $newName
            ]);

            if (isset($result['data']) && $result['data']) {
                return ResponseHelper::generate(true, 'VM renamed successfully.');
            }

            return ResponseHelper::generate(false, 'Failed to rename VM.', $result['data'] ?? null);

        } catch (Exception $e) {
            return ResponseHelper::generate(false, $e->getMessage());
        }
    }


    /**
     * Fix non-running guest agent
     */
    public function fixGuestAgent(string $node, int $vmid): array {
        try {
            // 1. First ensure agent is enabled in config with correct settings
            $configResult = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", [
                'agent' => 'enabled=1',
                'ostype' => 'l26'  // Linux 2.6+ kernel
            ]);

            // 2. Get current VM status
            $vmStatus = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");
            $isRunning = isset($vmStatus['data']['status']) && $vmStatus['data']['status'] === 'running';

            // 3. If VM is running, need to restart it
            if ($isRunning) {
                // Stop VM
                $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/stop");

                // Wait for VM to stop
                sleep(15);

                // Start VM
                $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/start");

                // Wait for VM to start
                sleep(20);
            }

            // 4. Check agent status after restart
            try {
                $agentStatus = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/agent/ping");
                $agentRunning = true;
            } catch (Exception $e) {
                $agentRunning = false;
            }

            return [
                'success' => true,
                'agent_enabled_in_config' => true,
                'agent_running' => $agentRunning,
                'vm_restarted' => $isRunning,
                'next_steps' => !$agentRunning ? [
                    'Install guest agent package inside VM:',
                    'For Ubuntu/Debian: apt install qemu-guest-agent',
                    'For CentOS/RHEL: yum install qemu-guest-agent',
                    'Then run: systemctl start qemu-guest-agent'
                ] : []
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to fix guest agent: " . $e->getMessage());
        }
    }

    /**
     * Check detailed guest agent status
     */
    public function getDetailedAgentStatus(string $node, int $vmid): array {
        try {
            // Get VM configuration
            $config = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/config");

            // Get current status
            $status = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

            // Try to ping agent
            try {
                $ping = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/agent/ping");
                $agentResponding = true;
            } catch (Exception $e) {
                $agentResponding = false;
            }

            return [
                'config_status' => [
                    'agent_enabled' => isset($config['data']['agent']) && strpos($config['data']['agent'], 'enabled=1') !== false,
                    'os_type' => $config['data']['ostype'] ?? 'unknown'
                ],
                'runtime_status' => [
                    'vm_status' => $status['data']['status'] ?? 'unknown',
                    'agent_responding' => $agentResponding
                ]
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to get agent status: " . $e->getMessage());
        }
    }

    /**
     * Enable QEMU Guest Agent for a VM
     */
    public function enableGuestAgent(string $node, int $vmid): array {
        try {
            // Enable QEMU Guest Agent in VM configuration
            $config = [
                'agent' => 1,  // Enable QEMU Guest Agent
                'ostype' => 'l26'  // Linux 2.6+/3.x/4.x Kernel
            ];

            $result = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", $config);

            return [
                'success' => true,
                'config' => $config,
                'response' => $result
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to enable guest agent: " . $e->getMessage());
        }
    }

    /**
     * Configure and start QEMU Guest Agent
     */
    public function configureGuestAgent(string $node, int $vmid): array {
        try {
            // First, enable the QEMU guest agent in VM config
            $configResult = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", [
                'agent' => 'enabled=1,fstrim_cloned_disks=1',
                'ostype' => 'l26'  // For Linux VMs
            ]);

            // Stop the VM if it's running
            $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/stop");

            // Wait for VM to stop
            sleep(10);

            // Start the VM to apply changes
            $startResult = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/status/start");

            // Check agent status (might take a few seconds to initialize)
            sleep(20);  // Wait for VM to fully start

            $status = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/agent/ping");

            return [
                'success' => true,
                'config_result' => $configResult,
                'start_result' => $startResult,
                'agent_status' => $status
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to configure guest agent: " . $e->getMessage());
        }
    }

    /**
     * Check Guest Agent status
     */
    public function checkGuestAgentStatus(string $node, int $vmid): array {
        try {
            // Get VM status including agent info
            $status = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

            // Try to ping the agent
            try {
                $ping = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/agent/ping");
                $agentRunning = true;
            } catch (Exception $e) {
                $agentRunning = false;
            }

            return [
                'success' => true,
                'agent_enabled' => isset($status['data']['agent']) && $status['data']['agent'] == 1,
                'agent_running' => $agentRunning,
                'status' => $status['data']
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to check guest agent status: " . $e->getMessage());
        }
    }

    /**
     * Configure network settings for a VM
     */
    public function configureNetwork(string $node, int $vmid, array $networkParams): array {
        try {
            // Validate IP format
            if (!filter_var($networkParams['ip'], FILTER_VALIDATE_IP)) {
                throw new Exception('Invalid IP address format');
            }
            if (!filter_var($networkParams['gateway'], FILTER_VALIDATE_IP)) {
                throw new Exception('Invalid gateway IP format');
            }

            // Format the network configuration
            $netConfig = [
                'ipconfig0' => sprintf(
                    'ip=%s/%s,gw=%s',
                    $networkParams['ip'],
                    $networkParams['subnet_mask'] ?? '24',
                    $networkParams['gateway']
                )
            ];

            // Add optional network parameters
            if (isset($networkParams['nameserver'])) {
                $netConfig['nameserver'] = $networkParams['nameserver'];
            }
            if (isset($networkParams['searchdomain'])) {
                $netConfig['searchdomain'] = $networkParams['searchdomain'];
            }

            // Apply network configuration
            $result = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", $netConfig);

            return [
                'success' => true,
                'config' => $netConfig,
                'response' => $result
            ];

        } catch (Exception $e) {
            throw new Exception("Failed to configure network: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function setVMPassword(string $node, int $vmid)
    {
        $response = $this->makeRequest('POST', "/nodes/{$node}/qemu/{$vmid}/config", [
            'ciuser' => 'root',
            'cipassword' => 'Password@@24'
        ]);
        return $response;
        if ($response['success']) {
            return response()->json('SSH credentials injected successfully');
        }
        return response()->json('SSH credentials failed to inject!');
    }

    /**
     * Get the next available VMID
     *
     * @return int
     * @throws Exception
     */
    private function getNextVMID(): int
    {
        $response = $this->makeRequest('GET', 'cluster/nextid');

        if (!isset($response['data'])) {
            throw new Exception('Failed to get next VMID');
        }

        return (int)$response['data'];
    }

    /**
     * Create a VM from template
     *
     * @param string $node Node name
     * @param int $templateId Template VMID to clone from
     * @param array $params Clone configuration parameters
     * @throws Exception
     */
    public function cloneVM(string $node, int $templateId, array $params)
    {
        if (!isset($params['newid'])) {
            $params['newid'] = $this->getNextVMID();
        }

        // Set default values
        $defaults = [
            'full' => 1,  // Full clone (not linked)
        ];

        $params = array_merge($defaults, $params);

        $response = $this->makeRequest(
            'POST',
            "nodes/{$node}/qemu/{$templateId}/clone",
            $params
        );

        if (!isset($response['data'])) {
            return ResponseHelper::generate(false, 'Failed to create VM!');
        }

        $successResponse = [
            'node' => $node,
            'vmid' => $params['newid'],
            'data' => $response['data']
        ];

        return ResponseHelper::generate(true, 'VM Created successfully', $successResponse);
    }

    /**
     * Set VM configuration
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param array $params Configuration parameters
     * @return array
     * @throws Exception
     */
    public function setVMConfig(string $node, int $vmid, array $params): array
    {
        return $this->makeRequest(
            'POST',
            "nodes/{$node}/qemu/{$vmid}/config",
            $params
        );
    }

    /**
     * Attach disk to VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param array $params Disk parameters
     * @throws Exception
     */
    public function attachDisk(string $node, int $vmid, array $params)
    {
        // Validate required parameters
        if (!isset($params['storage']) || !isset($params['size'])) {
            return ResponseHelper::generate(false,'Storage and size parameters are required');
        }

        try {
            // Get current VM configuration
            $config = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/config");

            // Find next available SCSI disk ID
            $nextId = 0;
            foreach ($config['data'] as $key => $value) {
                if (preg_match('/^scsi(\d+)$/', $key, $matches)) {
                    $nextId = max($nextId, (int)$matches[1] + 1);
                }
            }

            // Prepare disk parameters - Fixed format
//            $size = preg_match('/^\d+G$/', $params['size']) ? $params['size'] : "{$params['size']}";
            $size = preg_replace('/\D/', '', $params['size']);;

            // Base disk specification - Note the "volume=0" format
            $diskParams = [
                "scsi{$nextId}" => "{$params['storage']}:{$size}"
            ];

            // Add optional parameters
            $optionalParams = '';
            if (isset($params['format'])) {
                $optionalParams .= ",format={$params['format']}";
            }
            if (isset($params['cache'])) {
                $optionalParams .= ",cache={$params['cache']}";
            }
            if (isset($params['iothread'])) {
                $optionalParams .= ",iothread={$params['iothread']}";
            }

            // Append optional parameters if any exist
            if (!empty($optionalParams)) {
                $diskParams["scsi{$nextId}"] .= $optionalParams;
                $diskParams["net0"] = 'virtio,bridge=vmbr0,firewall=1';

                $diskParams['boot'] = "order=scsi{$nextId};net0";
//                $diskParams['boot'] = "order=ide2;scsi{$nextId};net0";
            }

            // Apply configuration
            $result = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", $diskParams);

            if (!isset($result['data'])) {
                return ResponseHelper::generate(false,"Failed to attach disk: No response data received");
            }

            $successResponse = [
                'disk_id' => "scsi{$nextId}",
                'config' => $diskParams,
                'data' => $result['data']
            ];

            return ResponseHelper::generate(true, 'Disk attach success', $successResponse);

        } catch (Exception $e) {
            return ResponseHelper::generate(false,"Failed to attach disk: " . $e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function detachDisk(string $node, int $vmid, string $disk, bool $type = false)
    {
        try {
            // API endpoint to remove a disk
            $result = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", [
                'delete' => $disk, // Specify the disk to delete (e.g., 'scsi0')
                'force' => $type
            ]);

            if (!isset($result['data'])) {
                return ResponseHelper::generate(false,"Failed to detach disk");
            }

            $successResponse = [
                'node' => $node,
                'vmid' => $vmid,
                'disk' => $disk,
                'data' => $result['data']
            ];

            return ResponseHelper::generate(true, 'Disk attach success', $successResponse);
        } catch (Exception $e) {
            return ResponseHelper::generate(false,"Failed to detach disk: " . $e->getMessage());
        }
    }

    /**
     * Generate SSH Key Pair
     *
     * @param string $keyName Name for the key files
     * @param string $keyPath Path to store keys (default: storage/app/ssh)
     * @param string $type Key type (rsa, ed25519)
     * @param int $bits Key bits for RSA
     * @return array
     * @throws Exception
     */
    public function generateSSHKey($path)
    {
        $name = preg_replace('/[^a-z0-9]+/i', '_', strtolower(request()->name));
        $keyPath = $path;
        $keyName = $name;
        $fullPath = "{$keyPath}/{$keyName}";

        // Ensure directory exists
        if (!file_exists($keyPath)) {
            mkdir($keyPath, 0700, true);
        }

        // Execute ssh-keygen
        exec("ssh-keygen -t rsa -b 2048 -f {$fullPath} -N 'pass'");


        $response = [
            'private_key_path' => $fullPath,
            'private_key' => file_get_contents($fullPath),
            'public_key_path' => $fullPath . '.pub',
            'public_key' => file_get_contents($fullPath . '.pub')
        ];

        return ResponseHelper::generate(true,'SSH key generated successfully', $response);
    }

    /**
     * Attach SSH Key to VM Cloud-Init Configuration
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param string $publicKey SSH public key content
     * @return array
     * @throws Exception
     */
    public function attachSSHKey(string $node, int $vmid, string $publicKey): array
    {
        // First check if VM exists and uses cloud-init
        $vmConfig = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/config");

        if (!isset($vmConfig['data'])) {
            throw new Exception("VM {$vmid} not found on node {$node}");
        }


        // Clean up the public key
        $publicKey = trim($publicKey);

        // Set SSH key in cloud-init config
        return $this->setVMConfig($node, $vmid, [
            'sshkeys' => $publicKey
        ]);
    }

    /**
     * Create Ubuntu VM with SSH Key
     *
     * @param string $node Node name
     * @param array $params VM configuration parameters
     * @param string|null $sshKey SSH public key content
     * @return array
     * @throws Exception
     */
    public function createUbuntuVMWithSSH(
        string  $node,
        array   $params,
        ?string $sshKey = null
    ): array
    {
        // Generate new SSH key if none provided
        if ($sshKey === null) {
            $keyName = $params['name'] ?? 'vm-' . time();
            $sshKeyPair = $this->generateSSHKey($keyName);
            $sshKey = file_get_contents($sshKeyPair['public_key_path']);
        }

        // Add cloud-init specific defaults
        $cloudInitDefaults = [
            'ostype' => 'other',
            'cores' => 2,
            'memory' => 2048,
            'cpu' => 'host',
            'agent' => 1,
            'bios' => 'ovmf',
            'net0' => 'virtio,bridge=vmbr0',
            'cicustom' => 'user=local:snippets/cloud-init-user.yml',
            'ipconfig0' => 'ip=dhcp',
            'sshkeys' => urlencode($sshKey)
        ];

        $params = array_merge($cloudInitDefaults, $params);

        // Create VM
        $result = $this->createVM($node, $params);

        return array_merge($result, [
            'ssh_key' => $sshKey
        ]);
    }

    public function configureVMCloudInitNetwork(string $node, int $vmid, $ip, $gateway, $netmask)
    {
        $params = [
            "ipconfig0" => "ip={$ip}/{$netmask},gw={$gateway}"
        ];

        $response = $this->makeRequest('PUT', "nodes/{$node}/qemu/{$vmid}/config", $params);

        if (!isset($response['data'])){
            $successResponse = [
                'node' => $node,
                'vmid' => $vmid,
                'public_ip' => $ip,
                'netmask' => $netmask,
                'gateway' => $gateway,
            ];
            return ResponseHelper::generate(true,'Network configured successfully', $successResponse);
        }
        return ResponseHelper::generate(false,'Network configure fail!', $response['data']);
    }

    /**
     * @throws Exception
     */
    public function updateVmConfig(string $node, int $vmid, $params)
    {
        $response = $this->makeRequest('PUT', "nodes/{$node}/qemu/{$vmid}/config", $params);

        if (!isset($response['data'])){
            $successResponse = [
                'node' => $node,
                'vmid' => $vmid,
            ];
            return ResponseHelper::generate(true,'Config updated successfully', $successResponse);
        }
        return ResponseHelper::generate(false,'Config update failed!', $response['data']);
    }

    /**
     * @throws Exception
     */
    public function resizeVm(string $node, int $vmid, $params)
    {
        // Disk resize parameters
        $resizeParams = [
            'disk' => 'scsi0', // Disk you want to resize (e.g., scsi0, virtio0)
            'size' => '+10G',  // Size to add (e.g., +10G for 10GB)
        ];
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/config", $params);

        if (!isset($response['data'])){
            $successResponse = [
                'node' => $node,
                'vmid' => $vmid,
            ];
            return ResponseHelper::generate(true,'Config updated successfully', $successResponse);
        }
        return ResponseHelper::generate(false,'Config update failed!', $response['data']);
    }

    /**
     * @throws Exception
     */
    public function fetchAvailableIPs($node)
    {
//        $params = ['type' => 'bridge'];
        $response = $this->makeRequest('GET', "nodes/{$node}/network");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Network list fail!', $response['data']);
        }
        return ResponseHelper::generate(true,'Network list', $response['data']);
    }


    public function applyCloudInitVM($node, $vmid)
    {
        return $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/cloudinit");
    }

    /**
     * Destroy the vm (also delete all used/owned volumes)
     * DELETE /api2/json/nodes/{node}/qemu/{vmid}
     * @param string   $node    The cluster node name.
     * @param integer  $vmid    The (unique) ID of the VM.
     * @param array    $data
     */
    public function destroyVm($node, $vmid, $data = array())
    {
        $response = $this->makeRequest('DELETE',"nodes/$node/qemu/$vmid", $data);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Destroy the vm fail!', $response['data']);
        }
        return ResponseHelper::generate(true,'Destroyed the vm', $response['data']);
    }

    /**
     * Delete a Virtual Machine
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param bool $force Force delete even if running (will stop VM first)
     * @param bool $purge Remove VM disk
     * @throws Exception
     */
    public function deleteVM(string $node, int $vmid, bool $force = false, bool $purge = true)
    {
        // Check if VM exists
        $vmStatus = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

        if (!isset($vmStatus['data'])) {
            throw new Exception("VM {$vmid} not found on node {$node}");
        }

        // If VM is running and force is true, stop it first
        if ($vmStatus['data']['status'] === 'running') {
            if (!$force) {
                throw new Exception("Cannot delete running VM. Either stop the VM first or use force option.");
            }

            // Stop the VM
            $this->stopVM($node, $vmid);

            // Wait for VM to stop (max 30 seconds)
            $timeout = 30;
            $start = time();
            do {
                sleep(2);
                $currentStatus = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/status/current");
                if ($currentStatus['data']['status'] === 'stopped') {
                    break;
                }
            } while (time() - $start < $timeout);

            // Check if VM actually stopped
            if ($currentStatus['data']['status'] !== 'stopped') {
                throw new Exception("Failed to stop VM {$vmid} within timeout period");
            }
        }

        // Delete the VM
        $params = [];
        if ($purge) {
            $params['purge'] = 1; // Also remove disk
            $params['destroy-unreferenced-disks'] = 1; // Remove unreferenced disks
        }

        try {
            $response = $this->makeRequest('DELETE', "nodes/$node/qemu/$vmid", $params);
            $successResponse = [
                'data' => $response['data'],
                'node' => $node,
                'vmid' => $vmid,
            ];
            return ResponseHelper::generate(true, 'VM Deleted successfully', $successResponse);
        } catch (Exception $e) {
            return ResponseHelper::generate(false, "Failed to delete VM {$vmid}: " . $e->getMessage());
        }
    }

    /**
     * Delete multiple Virtual Machines
     *
     * @param string $node Node name
     * @param array $vmids Array of VM IDs
     * @param bool $force Force delete even if running
     * @param bool $purge Remove VM disks
     * @return array
     */
    public function deleteMultipleVMs(string $node, array $vmids, bool $force = false, bool $purge = true): array
    {
        $results = [];
        $errors = [];

        foreach ($vmids as $vmid) {
            try {
                $results[$vmid] = $this->deleteVM($node, $vmid, $force, $purge);
            } catch (Exception $e) {
                $errors[$vmid] = $e->getMessage();
            }
        }

        return [
            'success' => $results,
            'errors' => $errors
        ];
    }

    public function updateCredential($node, $vmid, $payload)
    {
        $params = [
            'cipassword' => $payload['password']
        ];

        try {
            $response = $this->makeRequest('POST', "/nodes/{$node}/qemu/{$vmid}/config", $params);
            $successResponse = [
                'data' => $response['data'],
                'node' => $node,
                'vmid' => $vmid,
            ];

            if (!isset($response['data'])){
                return ResponseHelper::generate(false,'Failed to Update VM Credential Password!');
            }

            return ResponseHelper::generate(true, 'VM Credential Password Updated successfully', $successResponse);
        } catch (\Exception $e){
            return ResponseHelper::generate(false, "Failed to Update VM Credential Password {$vmid}: " . $e->getMessage());
        }
    }

    public function injectSSHCredentials($node, $vmid, $username, $password, $sshKey = null)
    {
        $payload = [
            'ciuser' => $username,
            'cipassword' => $password
        ];

        if ($sshKey) {
            $payload['sshkeys'] = $sshKey;
        }

        $url = "/nodes/{$node}/qemu/{$vmid}/config";
        return $this->makeRequest('POST', "{$this->baseUrl}{$url}", $payload);
    }

    public function rebuildCloudInit($node, $vmid)
    {
        $url = "/nodes/{$node}/qemu/{$vmid}/cloudinit";
        return $this->makeRequest('POST', "{$this->baseUrl}{$url}");

        // Usage
        /*try {
            $proxmox = new ProxmoxAPI('https://your-proxmox-server', 'root', 'password');
            $node = 'node1';
            $vmid = 100;

            // Inject SSH credentials
            $response = $proxmox->injectSSHCredentials($node, $vmid, 'myuser', 'mypassword', 'ssh-rsa AAAAB3...user@domain');

            if ($response['success']) {
                echo "SSH credentials injected successfully.\n";
                $proxmox->rebuildCloudInit($node, $vmid);
            } else {
                echo "Failed to inject SSH credentials: " . $response['message'] . "\n";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }*/
    }

    /**
     * Node index.
     * @throws Exception
     */
    public function diskList(string $node)
    {
        $response = $this->makeRequest('GET', "nodes/{$node}/disks");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Disk list failed!', $response['data']);
        }

        $successResponse = [
            'node' => $node,
            'data' => $response['data']
        ];

        return ResponseHelper::generate(true,'Disk list successfully', $successResponse);
    }

    /**
     * PVE Managed storages..
     * $type directory, lvm, lvmthin, zfs,
     * @throws Exception
     */
    public function diskTypeList(string $node, string $type)
    {
        $response = $this->makeRequest('GET', "nodes/{$node}/disks/{$type}");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Disk fetch failed!', $response['data']);
        }

        $successResponse = [
            'node' => $node,
            'data' => $response['data']
        ];

        return ResponseHelper::generate(true,'Disk fetch successfully', $successResponse);
    }

    /**
     * PVE Managed storages..
     * $type directory, lvm, lvmthin, zfs,
     * @throws Exception
     */
    public function createDiskType(string $node, string $type, array $params)
    {
        $response = $this->makeRequest('POST', "nodes/{$node}/disks/{$type}", $params);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Disk fetch failed!', $response['data']);
        }

        $successResponse = [
            'node' => $node,
            'data' => $response['data']
        ];

        return ResponseHelper::generate(true,'Disk fetch successfully', $successResponse);
    }

    /**
     * PVE Managed storages..
     * $type directory, lvm, lvmthin, zfs,
     * @throws Exception
     */
    public function deleteDiskType(string $node, string $type, $name)
    {
        $response = $this->makeRequest('DELETE', "nodes/{$node}/disks/{$type}/{$name}");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Disk delete failed!', $response['data']);
        }

        $successResponse = [
            'node' => $node,
            'data' => $response['data']
        ];

        return ResponseHelper::generate(true,'Disk deleted successfully', $successResponse);
    }

    /**
     * QEMU monitor commands...
     * @throws Exception
     */
    public function vmMonitor(string $node, int $vmid, $params)
    {
        $params = ['command' => 'info'];
        $response = $this->makeRequest('POST', "nodes/{$node}/qemu/{$vmid}/monitor", $params);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Failed to fetch monitoring data!', $response['data']);
        }

        $successResponse = [
            'node' => $node,
            'data' => $response['data']
        ];

        return ResponseHelper::generate(true,'Data retrieved successfully', $successResponse);
    }

    /**
     * QEMU VM  RRDdata (Round Robin Database data)...
     * @throws Exception
     */
    public function vmRrddata(string $node, int $vmid, $params)
    {
        try {
            // Fixed URL by removing extra curly brace
            $response = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/rrddata", $params);

            if (!isset($response['data'])) {
                return ResponseHelper::generate(
                    false,
                    'No monitoring data available',
                    []
                );
            }

            $successResponse = [
                'node' => $node,
                'vmid' => $vmid,
                'data' => array_map(function($entry) {
                    return [
                        'time' => isset($entry['time']) ? date('Y-m-d H:i:s', $entry['time']) : null,
                        'cpu' => $entry['cpu'] ?? null,
                        'mem' => $entry['mem'] ?? null,
                        'netin' => $entry['netin'] ?? null,
                        'netout' => $entry['netout'] ?? null,
                        'diskread' => $entry['diskread'] ?? null,
                        'diskwrite' => $entry['diskwrite'] ?? null
                    ];
                }, $response['data'])
            ];

            return ResponseHelper::generate(true, 'Data retrieved successfully', $successResponse);

        } catch (Exception $e) {
            return ResponseHelper::generate(
                false,
                'Failed to fetch monitoring data: ' . $e->getMessage(),
                []
            );
        }
    }

    public function getVMMetrics(string $node, int $vmid, $params)
    {
        try {
            $response = $this->makeRequest('GET', "nodes/{$node}/qemu/{$vmid}/rrddata", $params);

            if (!isset($response['data']) || empty($response['data'])) {
                return ResponseHelper::generate(false, 'No metrics data available', []);
            }

            // Process and format the metrics
            $formattedMetrics = array_map(function($entry) {
                return [
                    'timestamp' => date('Y-m-d H:i:s', $entry['time']),

                    // CPU Metrics
                    'cpu' => [
                        'usage_percentage' => isset($entry['cpu']) ? round($entry['cpu'] * 100, 2) : null,
                    ],

                    // Memory Metrics
                    'memory' => [
                        'used_bytes' => $entry['mem'] ?? null,
                        'used_gb' => isset($entry['mem']) ? round($entry['mem'] / (1024 * 1024 * 1024), 2) : null,
                        'total_bytes' => $entry['maxmem'] ?? null,
                        'total_gb' => isset($entry['maxmem']) ? round($entry['maxmem'] / (1024 * 1024 * 1024), 2) : null,
                    ],

                    // Network Metrics
                    'network' => [
                        'in_bytes' => $entry['netin'] ?? null,
                        'in_mb' => isset($entry['netin']) ? round($entry['netin'] / (1024 * 1024), 2) : null,
                        'out_bytes' => $entry['netout'] ?? null,
                        'out_mb' => isset($entry['netout']) ? round($entry['netout'] / (1024 * 1024), 2) : null,
                    ],

                    // Disk I/O Metrics
                    'disk' => [
                        'read_bytes' => $entry['diskread'] ?? null,
                        'read_mb' => isset($entry['diskread']) ? round($entry['diskread'] / (1024 * 1024), 2) : null,
                        'write_bytes' => $entry['diskwrite'] ?? null,
                        'write_mb' => isset($entry['diskwrite']) ? round($entry['diskwrite'] / (1024 * 1024), 2) : null,
                    ]
                ];
            }, $response['data']);

            $successResponse = [
                'node' => $node,
                'vmid' => $vmid,
                'metrics' => $formattedMetrics,
                'summary' => [
                    'latest_cpu_usage' => end($formattedMetrics)['cpu']['usage_percentage'] ?? null,
                    'latest_memory_used_gb' => end($formattedMetrics)['memory']['used_gb'] ?? null,
                    'latest_network_in_mb' => end($formattedMetrics)['network']['in_mb'] ?? null,
                    'latest_network_out_mb' => end($formattedMetrics)['network']['out_mb'] ?? null,
                ]
            ];

            return ResponseHelper::generate(true, 'Metrics retrieved successfully', $successResponse);

        } catch (Exception $e) {
            return ResponseHelper::generate(
                false,
                'Failed to fetch metrics: ' . $e->getMessage(),
                []
            );
        }
    }
}
