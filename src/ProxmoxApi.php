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
}
