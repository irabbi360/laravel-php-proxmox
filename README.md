## Proxmox API Integration PHP Package
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
