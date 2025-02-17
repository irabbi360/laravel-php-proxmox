## Proxmox API Integration PHP Package

[![License](https://poser.pugx.org/irabbi360/laravel-php-proxmox/license)](https://packagist.org/packages/irabbi360/laravel-php-proxmox)
[![Latest Stable Version](https://poser.pugx.org/irabbi360/laravel-php-proxmox/version)](https://packagist.org/packages/irabbi360/laravel-php-proxmox)
[![Total Downloads](https://poser.pugx.org/irabbi360/laravel-php-proxmox/downloads)](https://packagist.org/packages/irabbi360/laravel-php-proxmox)
[![Daily Downloads](https://poser.pugx.org/irabbi360/laravel-php-proxmox/d/daily)](https://packagist.org/packages/irabbi360/laravel-php-proxmox)

This PHP Laravel Proxmox library allows, to interact with your Proxmox server via API.

> You find any errors, typos or you detect that something is not working as expected please open an [issue](https://github.com/irabbi360/laravel-php-proxmox/issues/new). I'll try to release a fix asap.

## Installation

You can install the package via composer:

```bash
composer require irabbi360/laravel-php-proxmox
```

You can publish the config file with:
```bash
php artisan vendor:publish --tag=proxmox-config
```

Add this in .env from the proxmox.php config file
```bash
PROXMOX_HOST=
PROXMOX_USER=
PROXMOX_PASSWORD=
PROXMOX_REALM=
PROXMOX_PORT=
```

## How to use
To use the Proxmox functionality, you can call the respective facades in your controller. Import the required facades based on the functionality you need. Here's how you can utilize them:

- For Node-related operations, use the `ProxmoxNodeVM` facade.
- For Cluster-related operations, use the `ProxmoxCluster` facade.
- For Storage-related operations, use the `ProxmoxStorage` facade.

Make sure to import the corresponding facades into your controller before using them.

```bash
use Irabbi360\Proxmox\Facades\ProxmoxNodeVM;

public function vmVersion()
{
  return ProxmoxNodeVM::version();
}
```

```bash
use Irabbi360\Proxmox\Facades\ProxmoxCluster;

public function cluster()
{
  return ProxmoxCluster::version();
}
```

```bash
use Irabbi360\Proxmox\Facades\ProxmoxStorage;

public function storage()
{
  return ProxmoxStorage::version();
}
```

```bash
public function createVm($node, Request $request)
{
    $params = [
        'name' => $request->name,
        'cores' => $request->cores,
        'sockets' => $request->sockets,
        'memory' => $request->memory,
        'ostype' => $request->ostype,
        'onboot' => 1,
        'scsihw' => 'virtio-scsi-pci',
        'bootdisk' => 'scsi0',
        'net0' => 'virtio,bridge=vmbr0'
    ];

    return ProxmoxNodeVM::createVM($node, $params);
}
```

```bash
public function vmStart(string $node, int $vmId)
{
    return ProxmoxNodeVM::startVM($node, $vmId);
}

public function vmStop(string $node, int $vmId)
{
    return ProxmoxNodeVM::stopVM($node, $vmId);
}
```

## Access

```php
ProxmoxAccess::access()
ProxmoxAccess::acl()
ProxmoxAccess::updateAcl(array $data)
ProxmoxAccess::createTicket(array $data)
```

## Domains

```php
ProxmoxAccess::domains()
ProxmoxAccess::addDomain(array $data)
ProxmoxAccess::domainsRealm($realm)
ProxmoxAccess::updateDomain($realm, array $data)
ProxmoxAccess::deleteDomain($realm)
```

## Groups

```php
ProxmoxAccess::groups()
ProxmoxAccess::createGroup(array $data)
ProxmoxAccess::groupId($groupid)
ProxmoxAccess::updateGroup($groupid, array $data)
ProxmoxAccess::deleteGroup($groupid)
```

## Roles

```php
ProxmoxAccess::roles()
ProxmoxAccess::createRole(array $data)
ProxmoxAccess::roleId($roleid)
ProxmoxAccess::updateRole($roleid, array $data)
ProxmoxAccess::deleteRole($roleid)
```

## Users

```php
ProxmoxAccess::users()
ProxmoxAccess::createUser(array $data)
ProxmoxAccess::getUser($userid)
ProxmoxAccess::updateUser($userid, array $data)
ProxmoxAccess::deleteUser($userid)
ProxmoxAccess::changeUserPassword(array $data)
```

## Cluster

```php
ProxmoxCluster::cluster()
ProxmoxCluster::getClusterLog()
ProxmoxCluster::nextVmid($vmid = null)
ProxmoxCluster::options()
ProxmoxCluster::setOptions(array $data)
ProxmoxCluster::resources($type = null)
ProxmoxCluster::status()
ProxmoxCluster::tasks()
```

## Backup

```php
ProxmoxCluster::listBackup()
ProxmoxCluster::createBackup(array $data)
ProxmoxCluster::backupId($id)
ProxmoxCluster::updateBackup($id, array $data)
ProxmoxCluster::deleteBackup($id)
```

## Config

```php
ProxmoxCluster::config()
ProxmoxCluster::listConfigNodes()
ProxmoxCluster::configTotem()
```

## Firewall

```php
ProxmoxCluster::firewall()
ProxmoxCluster::firewallListAliases()
ProxmoxCluster::createFirewallAliase(array $data)
ProxmoxCluster::getFirewallAliasesName($name)
ProxmoxCluster::updateFirewallAliase($name, array $data)
ProxmoxCluster::removeFirewallAliase($name)
ProxmoxCluster::firewallListGroups()
ProxmoxCluster::createFirewallGroup(array $data)
ProxmoxCluster::firewallGroupsGroup($group)
ProxmoxCluster::createRuleFirewallGroup($group, array $data)
ProxmoxCluster::removeFirewallGroup($group)
ProxmoxCluster::firewallGroupsGroupPos($group, $pos)
ProxmoxCluster::setFirewallGroupPos($group, $pos, array $data)
ProxmoxCluster::removeFirewallGroupPos($group, $pos)
ProxmoxCluster::firewallListIpset()
ProxmoxCluster::createFirewallIpset(array $data)
ProxmoxCluster::firewallIpsetName($name)
ProxmoxCluster::addFirewallIpsetName($name, array $data)
ProxmoxCluster::deleteFirewallIpsetName($name)
ProxmoxCluster::firewallListRules()
ProxmoxCluster::createFirewallRules(array $data)
ProxmoxCluster::firewallRulesPos($pos)
ProxmoxCluster::setFirewallRulesPos($pos, array $data)
ProxmoxCluster::deleteFirewallRulesPos($pos)
ProxmoxCluster::firewallListMacros()
ProxmoxCluster::firewallListOptions()
ProxmoxCluster::setFirewallOptions(array $data)
ProxmoxCluster::firewallListRefs()
```

## HA

```php
ProxmoxCluster::getHaGroups()
ProxmoxCluster::HaGroups($group)
ProxmoxCluster::getHAResources()
```

## Replication

```php
ProxmoxCluster::replication()
ProxmoxCluster::createReplication(array $data)
ProxmoxCluster::replicationId($id)
ProxmoxCluster::updateReplication($id, array $data)
ProxmoxCluster::deleteReplication($id)
```

## Pools

```php
ProxmoxPools::pools()
ProxmoxPools::poolsId($poolid)
ProxmoxPools::putPool($poolid, array $data)
```

## Storage

```php
ProxmoxStorage::storage($type = null)
ProxmoxStorage::createStorage(array $data)
ProxmoxStorage::getStorage($storage)
ProxmoxStorage::updateStorage($storage, array $data)
ProxmoxStorage::deleteStorage($storage)
```

## Nodes

```php
ProxmoxNode::version()
ProxmoxNode::getNodes()
ProxmoxNode::apt($node)
ProxmoxNode::aplinfo($node)
ProxmoxNode::downloadTemplate($node, array $data)
ProxmoxNode::dns($node)
ProxmoxNode::setDns($node, array $data)
ProxmoxNode::execute($node, array $data)
ProxmoxNode::migrateAll($node, array $data)
ProxmoxNode::netstat($node)
ProxmoxNode::report($node)
ProxmoxNode::rrd($node, $ds = null, $timeframe = null)
ProxmoxNode::rrddata($node, $timeframe = null)
ProxmoxNode::spiceShell($node, array, $data)
ProxmoxNode::startAll($node, array $data)
ProxmoxNode::reboot($node, array, $data)
ProxmoxNode::stopAll($node, array $data)
ProxmoxNode::subscription($node)
ProxmoxNode::updateSubscription($node, array $data)
ProxmoxNode::setSubscription($node, $data = array())
ProxmoxNode::syslog($node, $limit = null, $start = null, $since = null, $until = null)
ProxmoxNode::time($node)
ProxmoxNode::setTime($node, array $data)
ProxmoxNode::version($node)
ProxmoxNode::createVNCShell($node, array $data)
ProxmoxNode::vNCWebSocket($node, $port = null, $vncticket = null)
```

## Apt

```php
ProxmoxNode::apt($node)
ProxmoxNode::updateApt($node, array $data)
ProxmoxNode::aptChangelog($node, $name = null)
ProxmoxNode::aptUpdate($node)
ProxmoxNode::createAptUpdate(array $data)
```

## Ceph

```php
ProxmoxNode::ceph($node)
ProxmoxNode::cephFlags($node)
ProxmoxNode::setCephFlags($node, $flag, array $data)
ProxmoxNode::unsetCephFlags($node, $flag)
ProxmoxNode::createCephMgr($node, array $data)
ProxmoxNode::destroyCephMgr($node, $id)
ProxmoxNode::cephMon($node)
ProxmoxNode::createCephMon($node, array $data)
ProxmoxNode::destroyCephMon($node, $monid)
ProxmoxNode::cephOsd($node)
ProxmoxNode::createCephOsd($node, array $data)
ProxmoxNode::destroyCephOsd($node, $osdid)
ProxmoxNode::cephOsdIn($node, $osdid, array $data)
ProxmoxNode::cephOsdOut($node, $osdid, array $data)
ProxmoxNode::getCephPools($node)
ProxmoxNode::createCephPool($node, array $data)
ProxmoxNode::destroyCephPool($node)
ProxmoxNode::cephConfig($node)
ProxmoxNode::cephCrush($node)
ProxmoxNode::cephDisks($node)
ProxmoxNode::createCephInit($node, array $data)
ProxmoxNode::cephLog($node, $limit = null, $start = null)
ProxmoxNode::cephRules($node)
ProxmoxNode::cephStart($node, array $data)
ProxmoxNode::cephStop($node, array $data)
ProxmoxNode::cephStatus($node)
```

## Disks

```php
ProxmoxNode::getDisks($node)
ProxmoxNode::disk($node, array $data)
ProxmoxNode::disksList($node)
ProxmoxNode::disksSmart($node, $disk = null)
```

## Nodes Firewall

```php
ProxmoxNode::firewall($node)
ProxmoxNode::firewallRules($node)
ProxmoxNode::createFirewallRule($node, $data = array())
ProxmoxNode::firewallRulesPos($node, $pos)
ProxmoxNode::setFirewallRulePos($node, $pos, $data = array())
ProxmoxNode::deleteFirewallRulePos($node, $pos)
ProxmoxNode::firewallRulesLog($node)
ProxmoxNode::firewallRulesOptions($node)
ProxmoxNode::setFirewallRuleOptions($node, $data = array())
```

## Lxc

```php
ProxmoxNode::lxc($node)
ProxmoxNode::createLxc($node, $data = array())
ProxmoxNode::lxcVmid($node, $vmid)
ProxmoxNode::deleteLxc($node, $vmid)
ProxmoxNode::lxcFirewall($node, $vmid)
ProxmoxNode::lxcFirewallAliases($node, $vmid)
ProxmoxNode::createLxcFirewallAliase($node, $vmid, $data = array())
ProxmoxNode::lxcFirewallAliasesName($node, $vmid, $name)
ProxmoxNode::updateLxcFirewallAliaseName($node, $vmid, $name, $data = array())
ProxmoxNode::deleteLxcFirewallAliaseName($node, $vmid, $name)
ProxmoxNode::lxcFirewallIpset($node, $vmid)
ProxmoxNode::createLxcFirewallIpset($node, $vmid, $data = array())
ProxmoxNode::lxcFirewallIpsetName($node, $vmid, $name)
ProxmoxNode::addLxcFirewallIpsetName($node, $vmid, $name, $data = array())
ProxmoxNode::deleteLxcFirewallIpsetName($node, $vmid, $name)
ProxmoxNode::lxcFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
ProxmoxNode::updateLxcFirewallIpsetNameCidr($node, $vmid, $name, $cidr, $data = array())
ProxmoxNode::deleteLxcFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
ProxmoxNode::lxcFirewallRules($node, $vmid)
ProxmoxNode::createLxcFirewallRules($node, $vmid, $data = array())
ProxmoxNode::lxcFirewallRulesPos($node, $vmid, $pos)
ProxmoxNode::setLxcFirewallRulesPos($node, $vmid, $pos, $data = array())
ProxmoxNode::deleteLxcFirewallRulesPos($node, $vmid, $pos)
ProxmoxNode::lxcFirewallLog($node, $vmid, $limit = null, $start = null)
ProxmoxNode::lxcFirewallOptions($node, $vmid)
ProxmoxNode::setLxcFirewallOptions($node, $vmid, $data = array())
ProxmoxNode::lxcSnapshot($node, $vmid)
ProxmoxNode::createLxcSnapshot($node, $vmid, $data = array())
ProxmoxNode::lxcSnapname($node, $vmid, $snapname)
ProxmoxNode::deleteLxcSnapshot($node, $vmid, $snapname)
ProxmoxNode::lxcSnapnameConfig($node, $vmid, $snapname)
ProxmoxNode::lxcSnapshotConfig($node, $vmid, $snapname, $data = array())
ProxmoxNode::lxcSnapshotRollback($node, $vmid, $snapname, $data = array())
ProxmoxNode::lxcStatus($node, $vmid)
ProxmoxNode::lxcCurrent($node, $vmid)
ProxmoxNode::lxcResume($node, $vmid, $data = array())
ProxmoxNode::lxcShutdown($node, $vmid, $data = array())
ProxmoxNode::lxcStart($node, $vmid, $data = array())
ProxmoxNode::lxcStop($node, $vmid, $data = array())
ProxmoxNode::lxcReboot($node, $vmid, $data = array())
ProxmoxNode::lxcSuspend($node, $vmid, $data = array())
ProxmoxNode::lxcClone($node, $vmid, $data = array())
ProxmoxNode::lxcConfig($node, $vmid)
ProxmoxNode::setLxcConfig($node, $vmid, $data = array())
ProxmoxNode::lxcFeature($node, $vmid)
ProxmoxNode::lxcMigrate($node, $vmid, $data = array())
ProxmoxNode::lxcResize($node, $vmid, $data = array())
ProxmoxNode::lxcRrd($node, $vmid, $ds = null, $timeframe = null)
ProxmoxNode::lxcRrddata($node, $vmid, $timeframe = null)
ProxmoxNode::lxcSpiceproxy($node, $vmid, $data = array())
ProxmoxNode::createLxcTemplate($node, $vmid, $data = array())
ProxmoxNode::createLxcVncproxy($node, $vmid, $data = array())
ProxmoxNode::lxcVncwebsocket($node, $vmid, $port = null, $vncticket = null)
```

## Network

```php
ProxmoxNode::network($node, $type = null)
ProxmoxNode::createNetwork($node, $data = array())
ProxmoxNode::revertNetwork($node)
ProxmoxNode::networkIface($node, $iface)
ProxmoxNode::updateNetworkIface($node, $iface, $data = array())
ProxmoxNode::deleteNetworkIface($node, $iface)
```

## Qemu

```php
ProxmoxNode::qemu($node)
ProxmoxNode::createQemu($node, $data = array())
ProxmoxNode::qemuVmid($node, $vmid)
ProxmoxNode::deleteQemu($node, $vmid, $data = array())
ProxmoxNode::qemuFirewall($node, $vmid)
ProxmoxNode::qemuFirewallAliases($node, $vmid)
ProxmoxNode::createQemuFirewallAliase($node, $vmid, $data = array())
ProxmoxNode::qemuFirewallAliasesName($node, $vmid, $name)
ProxmoxNode::updateQemuFirewallAliaseName($node, $vmid, $name, $data = array())
ProxmoxNode::deleteQemuFirewallAliaseName($node, $vmid, $name)
ProxmoxNode::qemuFirewallIpset($node, $vmid)
ProxmoxNode::createQemuFirewallIpset($node, $vmid, $data = array())
ProxmoxNode::qemuFirewallIpsetName($node, $vmid, $name)
ProxmoxNode::addQemuFirewallIpsetName($node, $vmid, $name, $data = array())
ProxmoxNode::deleteQemuFirewallIpsetName($node, $vmid, $name)
ProxmoxNode::qemuFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
ProxmoxNode::updateQemuFirewallIpsetNameCidr($node, $vmid, $name, $cidr, $data = array())
ProxmoxNode::deleteQemuFirewallIpsetNameCidr($node, $vmid, $name, $cidr)
ProxmoxNode::qemuFirewallRules($node, $vmid)
ProxmoxNode::createQemuFirewallRules($node, $vmid, $data = array())
ProxmoxNode::qemuFirewallRulesPos($node, $vmid, $pos)
ProxmoxNode::updateQemuFirewallRulesPos($node, $vmid, $pos, $data = array())
ProxmoxNode::deleteQemuFirewallRulesPos($node, $vmid, $pos)
ProxmoxNode::qemuFirewallLog($node, $vmid, $limit = null, $start = null)
ProxmoxNode::qemuFirewallOptions($node, $vmid)
ProxmoxNode::setQemuFirewallOptions($node, $vmid, $data = array())
ProxmoxNode::qemuFirewallRefs($node, $vmid)
ProxmoxNode::qemuSnapshot($node, $vmid)
ProxmoxNode::createQemuSnapshot($node, $vmid, $data = array())
ProxmoxNode::qemuSnapname($node, $vmid, $snapname)
ProxmoxNode::deleteQemuSnapshot($node, $vmid, $snapname)
ProxmoxNode::qemuSnapnameConfig($node, $vmid, $snapname)
ProxmoxNode::updateQemuSnapshotConfig($node, $vmid, $snapname, $data = array())
ProxmoxNode::QemuSnapshotRollback($node, $vmid, $snapname, $data = array())
ProxmoxNode::qemuStatus($node, $vmid)
ProxmoxNode::qemuCurrent($node, $vmid)
ProxmoxNode::qemuResume($node, $vmid, $data = array())
ProxmoxNode::qemuReset($node, $vmid, $data = array())
ProxmoxNode::qemuShutdown($node, $vmid, $data = array())
ProxmoxNode::qemuStart($node, $vmid, $data = array())
ProxmoxNode::qemuStop($node, $vmid, $data = array())
ProxmoxNode::qemuReboot($node, $vmid, $data = array())
ProxmoxNode::qemuSuspend($node, $vmid, $data = array())
ProxmoxNode::qemuAgent($node, $vmid, $data = array())
ProxmoxNode::qemuAgentExec($node, $vmid, $data = array())
ProxmoxNode::qemuAgentSetUserPassword($node, $vmid, $data = array())
ProxmoxNode::qemuClone($node, $vmid, $data = array())
ProxmoxNode::qemuConfig($node, $vmid)
ProxmoxNode::createQemuConfig($node, $vmid, $data = array())
ProxmoxNode::setQemuConfig($node, $vmid, $data = array())
ProxmoxNode::qemuFeature($node, $vmid)
ProxmoxNode::qemuMigrate($node, $vmid, $data = array())
ProxmoxNode::qemuMonitor($node, $vmid, $data = array())
ProxmoxNode::qemuMoveDisk($node, $vmid, $data = array())
ProxmoxNode::qemuPending($node, $vmid)
ProxmoxNode::qemuResize($node, $vmid, $data = array())
ProxmoxNode::qemuRrd($node, $vmid, $ds = null, $timeframe = null)
ProxmoxNode::qemuRrddata($node, $vmid, $timeframe = null)
ProxmoxNode::qemuSendkey($node, $vmid, $data = array())
ProxmoxNode::qemuSpiceproxy($node, $vmid, $data = array())
ProxmoxNode::createQemuTemplate($node, $vmid, $data = array())
ProxmoxNode::qemuUnlink($node, $vmid, $data = array())
ProxmoxNode::createQemuVncproxy($node, $vmid, $data = array())
ProxmoxNode::qemuVncwebsocket($node, $vmid, $port = null, $vncticket = null)
```

## Nodes Replication

```php
ProxmoxNode::replication($node)
ProxmoxNode::replicationId($node, $id)
ProxmoxNode::replicationLog($node, $id)
ProxmoxNode::replicationScheduleNow($node, $id, $data = array())
ProxmoxNode::replicationStatus($node, $id)
```

## Scan

```php
ProxmoxNode::scan($node)
ProxmoxNode::scanGlusterfs($node)
ProxmoxNode::scanIscsi($node)
ProxmoxNode::scanLvm($node)
ProxmoxNode::scanLvmthin($node)
ProxmoxNode::scanUsb($node)
ProxmoxNode::scanZfs($node)
```

## Service

```php
ProxmoxNode::Services($node)
ProxmoxNode::listService($node, $service)
ProxmoxNode::servicesReload($node, $service, $data = array())
ProxmoxNode::servicesRestart($node, $service, $data = array())
ProxmoxNode::servicesStart($node, $service, $data = array())
ProxmoxNode::servicesStop($node, $service, $data = array())
ProxmoxNode::servicesState($node, $service)
```

## Nodes Storage

```php
ProxmoxNode::storage($node, $content = null, $storage = null, $target = null, $enabled = null)
ProxmoxNode::getStorage($node, $storage)
ProxmoxNode::listStorageContent($node, $storage)
ProxmoxNode::storageContent($node, $storage, $data = array())
ProxmoxNode::storageContentVolume($node, $storage, $volume)
ProxmoxNode::copyStorageContentVolume($node, $storage, $volume, $data = array())
ProxmoxNode::deleteStorageContentVolume($node, $storage, $volume)
ProxmoxNode::storageRRD($node)
ProxmoxNode::storageRRDdata($node)
ProxmoxNode::storageStatus($node)
ProxmoxNode::storageUpload($node, $data = array())
```

## Tasks

```php
ProxmoxNode::Tasks($node, $errors = null, $limit = null, $vmid = null, $start = null)
ProxmoxNode::tasksUpid($node, $upid)
ProxmoxNode::tasksStop($node, $upid)
ProxmoxNode::tasksLog($node, $upid, $limit = null, $start = null)
ProxmoxNode::tasksStatus($node, $upid)
```

## Vzdump

```php
Nodes::createVzdump($node, $data = array())
Nodes::VzdumpExtractConfig($node)
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Fazle Rabbi](https://github.com/irabbi360)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
