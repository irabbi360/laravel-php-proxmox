<?php

namespace Irabbi360\Proxmox;

use Irabbi360\Proxmox\Helpers\ResponseHelper;

class ProxmoxStorage extends Proxmox
{
    /**
     * Storage index.
     * @param enum     $type   Only list storage of specific type
     */
    public function storage($type = null): array
    {
        $params['type'] = !empty($type) ? $type : null;
        $response = $this->makeRequest('GET','storage', $params);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Storage list fetch fail!');
        }
        return ResponseHelper::generate(true,'Storage list!', $response['data']);
    }

    /**
     * Create Storage
     *
     * @param array $params Storage configuration parameters
     * @return array
     * @throws \Exception
     */
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
            return ['success' => false, 'message' => 'Storage create fail.'];
        }
        return ['success' => true, 'data' => $response['data'], 'message' => 'Storage created successfully.'];
    }

    /**
     * Create Directory Storage
     *
     * @param string $name Storage name
     * @param string $path Directory path
     * @param array $options Additional options
     * @return array
     */
    public function createDirectoryStorage(string $name, string $path, array $options = []): array
    {
        // Create Directory Storage
        /*$params = [
            'storage' => 'local-storage',  // Name of the storage
            'type' => 'dir',              // Storage type (directory)
            'path' => '/mnt/local',       // Path to the storage directory
            'content' => 'images,iso',    // Types of content this storage can hold
            'shared' => false             // Whether the storage is shared across nodes
        ];*/

        $defaults = [
            'storage' => $name,
            'type' => 'dir',
            'path' => $path,
            'content' => 'images,iso,backup,vztmpl',
            'shared' => 0,
            'preallocation' => 0
        ];

        return $this->createStorage(array_merge($defaults, $options));
    }

    /**
     * Create NFS Storage
     *
     * @param string $name Storage name
     * @param string $server NFS server
     * @param string $export NFS export path
     * @param array $options Additional options
     * @return array
     */
    public function createNFSStorage(string $name, string $server, string $export, array $options = []): array
    {
        $defaults = [
            'storage' => $name,
            'type' => 'nfs',
            'server' => $server,
            'export' => $export,
            'content' => 'images,iso',
            'shared' => 1,
            'options' => 'soft,async'
        ];

        return $this->createStorage(array_merge($defaults, $options));
    }

    /**
     * Create LVM Storage
     *
     * @param string $name Storage name
     * @param string $vgname Volume group name
     * @param array $options Additional options
     * @return array
     */
    public function createLVMStorage(string $name, string $vgname, array $options = []): array
    {
        $defaults = [
            'storage' => $name,
            'type' => 'lvm',
            'vgname' => $vgname,
            'content' => 'images',
            'shared' => 0
        ];

        return $this->createStorage(array_merge($defaults, $options));
    }

    /**
     * Create CIFS Storage
     *
     * @param string $name Storage name
     * @param string $server CIFS server
     * @param string $share Share name
     * @param string $username Username
     * @param string $password Password
     * @param array $options Additional options
     * @return array
     * @throws \Exception
     */
    public function createCIFSStorage(
        string $name,
        string $server,
        string $share,
        string $username,
        string $password,
        array $options = []
    ): array {
        $defaults = [
            'storage' => $name,
            'type' => 'cifs',
            'server' => $server,
            'share' => $share,
            'username' => $username,
            'password' => $password,
            'content' => 'images,iso',
            'shared' => 1,
            'version' => '3.0'
        ];

        return $this->createStorage(array_merge($defaults, $options));
    }

    /**
     * Get Storage List
     *
     * @return array
     * @throws \Exception
     */
    public function getStorageList(): array
    {
        $response = $this->makeRequest('GET', 'storage');

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Storage list fetch fail!');
        }
        return ResponseHelper::generate(true,'Storage list!', $response['data']);
    }

    /**
     * Get Storage Details
     *
     * @param string $storage Storage name
     * @return array
     * @throws \Exception
     */
    public function getStorageDetails(string $storage): array
    {
        $response = $this->makeRequest('GET', "storage/{$storage}");

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Storage details fetch fail!');
        }
        return ResponseHelper::generate(true,'Storage details fetch successfully!', $response['data']);
    }

    /**
     * Delete Storage
     *
     * @param string $storage Storage name
     * @return array
     * @throws \Exception
     */
    public function deleteStorage(string $storage): array
    {
        $response = $this->makeRequest('DELETE', "storage/{$storage}");

        if (!isset($response['data'])){
            return ResponseHelper::generate(true,'Storage deleted successfully', $response['data']);
        }
        return ResponseHelper::generate(false,'Storage delete fail!', $response['data']);
    }

    /**
     * Update Storage Configuration
     *
     * @param string $storage Storage name
     * @param array $params Update parameters
     * @return array
     * @throws \Exception
     */
    public function updateStorage(string $storage, array $params): array
    {
        $response = $this->makeRequest('PUT', "storage/{$storage}", $params);

        if (!isset($response['data'])){
            return ResponseHelper::generate(false,'Storage update fail!');
        }
        return ResponseHelper::generate(true,'Storage updated successfully!', $response['data']);
    }
}
