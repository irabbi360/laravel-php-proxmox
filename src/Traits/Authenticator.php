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
        $cacheKey = "proxmox_" . hash('sha256', "{$this->hostname}_{$this->port}_{$this->username}_{$this->realm}_" . hash( $this->password ) );

        return \Cache::remember($cacheKey, now()->addMinutes(90), function () {
            try {
                $response = $this->sendPostRequest(
                    "https://{$this->hostname}:{$this->port}/api2/json/access/ticket",
                    [
                        'username' => "{$this->username}@{$this->realm}",
                        'password' => $this->password,
                    ],
                );

                if( isset( $response['data']['ticket'], $response['data']['CSRFPreventionToken'] ) ) {
                    return $response;
                }

                throw new \Exception("Authentication failed: Invalid response format");
            } catch (\Throwable $e) {
                throw new \Exception("Authentication failed: {$e->getMessage()}", 0, $e );
            }
        });
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
