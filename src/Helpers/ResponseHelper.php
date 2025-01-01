<?php
namespace Irabbi360\Proxmox\Helpers;

class ResponseHelper
{
    /**
     * Generate a JSON response
     *
     * @param bool $success Indicates if the operation was successful
     * @param string $message Response message
     * @param array|null $data Additional data to include in the response
     * @return \Illuminate\Http\JsonResponse
     */
    public static function generate(bool $success, string $message, array $data = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response);
    }
}
