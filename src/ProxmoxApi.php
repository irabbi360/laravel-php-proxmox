<?php

namespace Irabbi360\Proxmox;

use Exception;

class ProxmoxApi
{
    private $hostname;
    private $username;
    private $password;
    private $realm;
    private $port;
    private $ticket;
    private $csrf;

    public function __construct(string $hostname, string $username, string $password, string $realm = 'pam', int $port = 8006)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->realm = $realm;
        $this->port = $port;
    }

    /**
     * Authenticate with Proxmox API
     *
     * @return bool
     * @throws Exception
     */
    public function login(): bool
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://{$this->hostname}:{$this->port}/api2/json/access/ticket",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'username' => $this->username,
                'password' => $this->password,
                'realm' => $this->realm
            ])
        ]);

        $response = curl_exec($curl);

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($response === false) {
            throw new Exception('cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        if ($httpCode !== 200) {
            throw new Exception('Authentication failed');
        }
        $data = json_decode($response, true);

        if (!isset($data['data'])) {
            throw new Exception('Invalid response format');
        }
        $this->ticket = $data['data']['ticket'];
        $this->csrf = $data['data']['CSRFPreventionToken'];

        return true;
    }

    /**
     * Make API request to Proxmox
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint
     * @param array $params Request parameters
     * @return array
     * @throws Exception
     */
    public function request(string $method, string $endpoint, array $params = []): array
    {
        if (!$this->ticket) {
            $this->login();
        }

        $curl = curl_init();
        $url = "https://{$this->hostname}:{$this->port}/api2/json/{$endpoint}";

        $headers = [
            'Cookie: PVEAuthCookie=' . $this->ticket,
            'CSRFPreventionToken: ' . $this->csrf
        ];

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method
        ];

        if ($method === 'POST' || $method === 'PUT') {
            $curlOptions[CURLOPT_POSTFIELDS] = http_build_query($params);
        } elseif (!empty($params)) {
            $url .= '?' . http_build_query($params);
            $curlOptions[CURLOPT_URL] = $url;
        }

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($response === false) {
            throw new Exception('cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        if ($httpCode >= 400) {
            throw new Exception("API request failed with status code: {$httpCode}");
        }

        return json_decode($response, true);
    }

    /**
     * API version details, including some parts of the global datacenter config.
     *
     * @return array
     * @throws Exception
     */
    public function version(): array
    {
        return $this->request('GET', 'version');
    }

    /**
     * Get list of nodes
     *
     * @return array
     * @throws Exception
     */
    public function getNodes(): array
    {
        return $this->request('GET', 'nodes');
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
        return $this->request('GET', "nodes/{$node}/qemu");
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
        return $this->request('GET', "nodes/{$node}/qemu/{$vmid}/status/current");
    }

    /**
     * Start VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @return array
     * @throws Exception
     */
    public function startVM(string $node, int $vmid): array
    {
        return $this->request('POST', "nodes/{$node}/qemu/{$vmid}/status/start");
    }

    /**
     * Stop VM
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @return array
     * @throws Exception
     */
    public function stopVM(string $node, int $vmid): array
    {
        return $this->request('POST', "nodes/{$node}/qemu/{$vmid}/status/stop");
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
     * @return array
     * @throws Exception
     */
    public function createVM(string $node, array $params): array
    {
        // Get next available VMID if not provided
        if (!isset($params['vmid'])) {
            $params['vmid'] = $this->getNextVMID();
        }

        // Set default values if not provided
        $defaults = [
            'cores' => 1,
            'sockets' => 1,
            'memory' => 512,
            'ostype' => 'other',
            'onboot' => 1,
            'scsihw' => 'virtio-scsi-pci',
            'bootdisk' => 'scsi0',
            'net0' => 'virtio,bridge=vmbr0'
        ];

        $params = array_merge($defaults, $params);

        // Required parameters validation
        $required = ['vmid', 'name', 'ostype', 'memory', 'cores'];
        foreach ($required as $field) {
            if (!isset($params[$field])) {
                throw new Exception("Missing required parameter: {$field}");
            }
        }

        return $this->request('POST', "nodes/{$node}/qemu", $params);
    }

    /**
     * Get the next available VMID
     *
     * @return int
     * @throws Exception
     */
    private function getNextVMID(): int
    {
        $response = $this->request('GET', 'cluster/nextid');

        if (!isset($response['data'])) {
            throw new Exception('Failed to get next VMID');
        }

        return (int) $response['data'];
    }

    /**
     * Create a VM from template
     *
     * @param string $node Node name
     * @param int $templateId Template VMID to clone from
     * @param array $params Clone configuration parameters
     * @return array
     * @throws Exception
     */
    public function cloneVM(string $node, int $templateId, array $params): array
    {
        if (!isset($params['newid'])) {
            $params['newid'] = $this->getNextVMID();
        }

        // Set default values
        $defaults = [
            'full' => 1,  // Full clone (not linked)
            'name' => 'vm-' . $params['newid']
        ];

        $params = array_merge($defaults, $params);

        return $this->request(
            'POST',
            "nodes/{$node}/qemu/{$templateId}/clone",
            $params
        );
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
        return $this->request(
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
     * @return array
     * @throws Exception
     */
    public function attachDisk(string $node, int $vmid, array $params): array
    {
        // Validate required parameters
        if (!isset($params['storage']) || !isset($params['size'])) {
            throw new Exception('Storage and size parameters are required');
        }

        // Find next available SCSI disk ID
        $config = $this->request('GET', "nodes/{$node}/qemu/{$vmid}/config");
        $nextId = 0;

        while (isset($config['data']["scsi{$nextId}"])) {
            $nextId++;
        }

        $diskParams = [
            "scsi{$nextId}" => "{$params['storage']}:{$params['size']}"
        ];

        return $this->setVMConfig($node, $vmid, $diskParams);
    }

    /**
     * Rename a Virtual Machine
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param string $newName New name for the VM
     * @return array
     * @throws Exception
     */
    public function renameVM(string $node, int $vmid, string $newName): array
    {
        try {
            // First, check if VM exists
            $vmStatus = $this->request('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

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
        int $bits = 4096
    ): array {
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
        $vmConfig = $this->request('GET', "nodes/{$node}/qemu/{$vmid}/config");

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
        string $node,
        array $params,
        ?string $sshKey = null
    ): array {
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

    /**
     * Delete a Virtual Machine
     *
     * @param string $node Node name
     * @param int $vmid VM ID
     * @param bool $force Force delete even if running (will stop VM first)
     * @param bool $purge Remove VM disk
     * @return array
     * @throws Exception
     */
    public function deleteVM(string $node, int $vmid, bool $force = false, bool $purge = true): array
    {
        // Check if VM exists
        $vmStatus = $this->request('GET', "nodes/{$node}/qemu/{$vmid}/status/current");

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
                $currentStatus = $this->request('GET', "nodes/{$node}/qemu/{$vmid}/status/current");
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
            return $this->request('DELETE', "nodes/{$node}/qemu/{$vmid}", $params);
        } catch (Exception $e) {
            throw new Exception("Failed to delete VM {$vmid}: " . $e->getMessage());
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
}
