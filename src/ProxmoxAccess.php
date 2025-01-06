<?php

namespace Irabbi360\Proxmox;

use Irabbi360\Proxmox\Helpers\ResponseHelper;

class ProxmoxAccess extends Proxmox
{
    /**
     * Directory index.
     * Accessible by all authenticated users.
     * @throws \Exception
     */
    public function access()
    {
        $response = $this->makeRequest('GET', 'access');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access fail.');
        }

        return ResponseHelper::generate(true,'Access List', $response['data']);
    }

    /**
     * Authentication domain index
     * @throws \Exception
     */
    public function domains()
    {
        $response = $this->makeRequest('GET', 'access/domains');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access Domains fail.');
        }

        return ResponseHelper::generate(true,'Access Domains List', $response['data']);
    }

    /**
     * Add an authentication server.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function addDomain(array $data)
    {
        $response = $this->makeRequest('POST', 'access/domains', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access add fail.');
        }

        return ResponseHelper::generate(true,'Access add successfully', $response['data']);
    }

    /**
     * Get auth server configuration
     * @param string $realm Authentication domain ID
     * @throws \Exception
     */
    public function domainsRealm($realm)
    {
        $response = $this->makeRequest('GET', "/access/domains/$realm");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access Domain fail.');
        }

        return ResponseHelper::generate(true,'Access Domain details', $response['data']);
    }

    /**
     * Update authentication server settings.
     * @param string $realm Authentication domain ID
     * @param array $data
     * @throws \Exception
     */
    public function updateDomain($realm, array $data)
    {
        $response = $this->makeRequest('PUT', "access/domains/$realm", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access domain update fail.');
        }

        return ResponseHelper::generate(true,'Access domain update successfully', $response['data']);
    }

    /**
     * Delete an authentication server
     * @param string $realm Authentication domain ID
     * @throws \Exception
     */
    public function deleteDomain($realm)
    {
        $response = $this->makeRequest('DELETE', "access/domains/$realm");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access domain delete fail.');
        }

        return ResponseHelper::generate(true,'Access domain deleted successfully', $response['data']);
    }

    /**
     * Get groups list
     * @throws \Exception
     */
    public function groups()
    {
        $response = $this->makeRequest('GET', 'access/groups');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access groups fail.');
        }

        return ResponseHelper::generate(true,'Access groups', $response['data']);
    }

    /**
     * Create new group.
     * @param array $data
     * @throws \Exception
     */
    public function createGroup(array $data)
    {
        $response = $this->makeRequest('POST', 'access/groups', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access group create fail.');
        }

        return ResponseHelper::generate(true,'Access group created successfully', $response['data']);
    }

    /**
     * Get group configuration
     * @param string $groupid
     * @throws \Exception
     */
    public function groupId($groupid)
    {
        $response = $this->makeRequest('GET', "access/groups/$groupid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access group fail.');
        }

        return ResponseHelper::generate(true,'Access group details', $response['data']);
    }

    /**
     * Update group data.
     * @param string $groupid
     * @param array $data
     * @throws \Exception
     */
    public function updateGroup($groupid, array $data)
    {
        $response = $this->makeRequest('POST', "access/groups/$groupid", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access group update fail.');
        }

        return ResponseHelper::generate(true,'Access group updated successfully', $response['data']);
    }

    /**
     * Delete group.
     * @param string $groupid
     * @throws \Exception
     */
    public function deleteGroup($groupid)
    {
        $response = $this->makeRequest('DELETE', "access/groups/$groupid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access group delete fail.');
        }

        return ResponseHelper::generate(true,'Access group deleted successfully', $response['data']);
    }

    /**
     * Get roles
     * @throws \Exception
     */
    public function roles()
    {
        $response = $this->makeRequest('GET', 'access/roles');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access roles fail.');
        }

        return ResponseHelper::generate(true,'Access roles', $response['data']);
    }

    /**
     * Create new role.
     * @param array $data
     * @throws \Exception
     */
    public function createRole(array $data)
    {
        $response = $this->makeRequest('POST', 'access/roles', $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access role create fail.');
        }

        return ResponseHelper::generate(true,'Access role created successfully', $response['data']);
    }

    /**
     * Get role configuration
     * @param string $roleid
     * @throws \Exception
     */
    public function roleId($roleid)
    {
        $response = $this->makeRequest('GET', "access/roles/$roleid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access role fail.');
        }

        return ResponseHelper::generate(true,'Access role details', $response['data']);
    }

    /**
     * Update an existing role.
     * @param string $roleid
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateRole($roleid, array $data)
    {
        $response = $this->makeRequest('PUT', "access/roles/$roleid", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access role update fail.');
        }

        return ResponseHelper::generate(true,'Access role updated successfully', $response['data']);
    }

    /**
     * Delete role.
     * @param string $roleid
     * @throws \Exception
     */
    public function deleteRole($roleid)
    {
        $response = $this->makeRequest('DELETE', "access/roles/$roleid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access role delete fail.');
        }

        return ResponseHelper::generate(true,'Access role deleted successfully', $response['data']);
    }

    /**
     * Get users
     * @throws \Exception
     */
    public function users()
    {
        $response = $this->makeRequest('GET', 'access/users');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access users fail.');
        }

        return ResponseHelper::generate(true,'Access users list', $response['data']);
    }

    /**
     * Create new user.
     * @param array $data
     * @throws \Exception
     */
    public function createUser(array $data)
    {
        $response = $this->makeRequest('POST', 'access/users');

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user create fail.');
        }

        return ResponseHelper::generate(true,'Access user created successfully', $response['data']);
    }

    /**
     * Get user configuration
     * @param string $userid
     * @throws \Exception
     */
    public function getUser($userid)
    {
        $response = $this->makeRequest('GET', "access/users/$userid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user fail.');
        }

        return ResponseHelper::generate(true,'Access user details', $response['data']);
    }

    /**
     * Update user configuration.
     * @param string $userid
     * @param array $data
     * @throws \Exception
     */
    public function updateUser($userid, array $data)
    {
        $response = $this->makeRequest('PUT', "access/users/$userid", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user update fail.');
        }

        return ResponseHelper::generate(true,'Access user updated successfully', $response['data']);
    }

    /**
     * Delete user.
     * @param string $userid
     * @throws \Exception
     */
    public function deleteUser($userid)
    {
        $response = $this->makeRequest('DELETE', "access/users/$userid");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user delete fail.');
        }

        return ResponseHelper::generate(true,'Access user deleted successfully', $response['data']);
    }

    /**
     * Change user password.
     * @param array $data
     */
    public function changeUserPassword(array $data)
    {
        $response = $this->makeRequest('PUT', "access/password", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user password update fail.');
        }

        return ResponseHelper::generate(true,'Access user password updated successfully', $response['data']);
    }

    /**
     * Get Access Control List (ACLs).
     * @throws \Exception
     */
    public function acl()
    {
        $response = $this->makeRequest('GET', "access/acl");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user acl fail.');
        }

        return ResponseHelper::generate(true,'Access user acl', $response['data']);
    }

    /**
     * Update Access Control List (add or remove permissions).
     * @param array $data
     * @throws \Exception
     */
    public function updateAcl($data = array())
    {
        $response = $this->makeRequest('PUT', "access/acl", $data);

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user acl update fail.');
        }

        return ResponseHelper::generate(true,'Access user acl updated successfully', $response['data']);
    }

    /**
     * Create or verify authentication ticket.
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function createTicket(array $data)
    {
        $response = $this->makeRequest('POST', "access/ticket");

        if (!isset($response['data'])){
            ResponseHelper::generate(false,'Access user authentication fail.');
        }

        return ResponseHelper::generate(true,'Access user authentication successfully', $response['data']);
    }
}
