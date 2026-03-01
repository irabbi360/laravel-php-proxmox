<?php
namespace Irabbi360\Proxmox\Helpers;

class ResponseHelper
{
    /**
     * Generate a JSON response
     *
     * @param bool $success Indicates if the operation was successful
     * @param string $message Response message
     * @param array|string|null $data Additional data to include in the response
     * @return array
     */
    public static function generate(bool $success, string $message, array|string $data = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return $response;
    }
}
