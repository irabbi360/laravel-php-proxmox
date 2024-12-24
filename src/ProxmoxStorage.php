<?php

namespace Irabbi360\Proxmox;

class ProxmoxStorage extends Proxmox
{
    /**
     * @throws \Exception
     */
    public function storage()
    {
        return $this->makeRequest('GET','/version');
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

        return $this->makeRequest('POST', 'storage', $params);
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
        return $this->makeRequest('GET', 'storage');
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
        return $this->makeRequest('GET', "storage/{$storage}");
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
        return $this->makeRequest('DELETE', "storage/{$storage}");
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
        return $this->makeRequest('PUT', "storage/{$storage}", $params);
    }
}
