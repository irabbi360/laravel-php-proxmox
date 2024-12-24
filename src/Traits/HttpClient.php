<?php

namespace Irabbi360\Proxmox\Traits;

trait HttpClient
{
    public function sendPostRequest(string $url, array $data): array
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            throw new \Exception("Curl Error: $error");
        }

        return json_decode($response, true);
    }
}
