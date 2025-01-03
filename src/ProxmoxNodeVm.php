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
                return [
                    'success' => false,
                    'message' => "Cannot rename running VM. Please stop the VM first.",
                    'data' => null
                ];
            }

            // Set new name
            $result = $this->setVMConfig($node, $vmid, [
                'name' => $newName
            ]);

            if (isset($result['success']) && $result['success']) {
                return [
                    'success' => true,
                    'message' => "VM renamed successfully.",
                    'data' => $result['data'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => "Failed to rename VM.",
                'data' => $result['data'] ?? null
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
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
                throw new Exception('Failed to attach disk: No response data received');
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

    public function detachDisk($node, $vmid, $disk)
    {
        // API endpoint to remove a disk
        $result = $this->makeRequest('DELETE', "nodes/{$node}/qemu/{$vmid}/config", [
            'delete' => $disk, // Specify the disk to delete (e.g., 'scsi0')
        ]);

        return $result;
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
    public function generateSSHKey(
        string $keyName,
        string $keyPath = null,
        string $type = 'ed25519',
        int    $bits = 4096
    ): array
    {
        // Set default path if not provided
        if ($keyPath === null) {
            $keyPath = storage_path('app/ssh');
        }

        // Create directory if it doesn't exist
        if (!is_dir($keyPath)) {
            mkdir($keyPath, 0700, true);
        }

        $privateKeyPath = "{$keyPath}/{$keyName}";
        $publicKeyPath = "{$keyPath}/{$keyName}.pub";

        // Generate key pair
        $config = [
            'private_key_type' => $type === 'rsa' ? OPENSSL_KEYTYPE_RSA : OPENSSL_KEYTYPE_EC,
            'private_key_bits' => $bits,
        ];

        if ($type === 'ed25519') {
            $config['curve_name'] = 'ed25519';
        }

        $key = openssl_pkey_new($config);
        if (!$key) {
            throw new Exception('Failed to generate SSH key pair: ' . openssl_error_string());
        }

        // Export private key
        openssl_pkey_export($key, $privateKey);
        file_put_contents($privateKeyPath, $privateKey);
        chmod($privateKeyPath, 0600);

        // Export public key
        $keyDetails = openssl_pkey_get_details($key);
        $publicKey = "{$type} {$keyDetails['key']} {$keyName}";
        file_put_contents($publicKeyPath, $publicKey);
        chmod($publicKeyPath, 0644);

        return [
            'private_key_path' => $privateKeyPath,
            'public_key_path' => $publicKeyPath,
            'public_key' => $publicKey
        ];
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
            'sshkeys' => urlencode($publicKey)
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
            $response = $this->makeRequest('DELETE', "nodes/{$node}/qemu/{$vmid}", $params);
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
}
