## Proxmox API Integration PHP Package
This PHP Laravel Proxmox library allows, to interact with your Proxmox server and cluster via API.

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
Call like this in the controller. ProxmoxVM is a facades. import the facades `ProxmoxVM`.  
```bash
use Irabbi360\Proxmox\Facades\ProxmoxVM;

public function vmVersion()
{
  return ProxmoxVM::version();
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

    return ProxmoxVM::createVM($node, $params);
}
```

```bash
public function vmStart(string $node, int $vmId)
{
    return ProxmoxVM::startVM($node, $vmId);
}

public function vmStop(string $node, int $vmId)
{
    return ProxmoxVM::stopVM($node, $vmId);
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
