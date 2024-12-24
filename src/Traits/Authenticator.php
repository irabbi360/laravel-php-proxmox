<?php

namespace Irabbi360\Proxmox\Traits;

trait Authenticator
{
    use HttpClient;

    private $hostname;
    private $username;
    private $password;
    private $realm;
    private $port;

    public function __construct(string $hostname, string $username, string $password, string $realm = 'pam', int $port = 8006)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->realm = $realm;
        $this->port = $port;
    }

    /**
     * @throws \Exception
     */
    public function authenticate(): array
    {
        $url = "https://{$this->hostname}:{$this->port}/api2/json/access/ticket";
        $data = [
            'username' => "{$this->username}@{$this->realm}",
            'password' => $this->password,
        ];

        return $this->sendPostRequest($url, $data);
    }

    /**
     * Make API request to Proxmox
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE)
     * @param string $endpoint API endpoint
     * @param array $params Request parameters
     * @return array
     * @throws \Exception
     */
    public function makeRequest(string $method, string $endpoint, array $params = []): array
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
            throw new \Exception('cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        if ($httpCode >= 400) {
            throw new \Exception("API request failed with status code: {$httpCode}");
        }

        return json_decode($response, true);
    }
}
