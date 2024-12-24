<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Proxmox API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for connecting to your Proxmox server
    |
    */

    'hostname' => env('PROXMOX_HOST', 'proxmox.example.com'),
    'username' => env('PROXMOX_USER', 'root'),
    'password' => env('PROXMOX_PASSWORD', ''),
    'realm' => env('PROXMOX_REALM', 'pam'),
    'port' => env('PROXMOX_PORT', 8006),

//    'base_url' => env('PROXMOX_BASE_URL', 'https://<Proxmox_Server_IP>:8006'),
];
