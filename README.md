# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/modstore/laravel-enum-js.svg?style=flat-square)](https://packagist.org/packages/modstore/laravel-enum-js)
[![Build Status](https://img.shields.io/travis/modstore/laravel-enum-js/master.svg?style=flat-square)](https://travis-ci.org/modstore/laravel-enum-js)
[![Quality Score](https://img.shields.io/scrutinizer/g/modstore/laravel-enum-js.svg?style=flat-square)](https://scrutinizer-ci.com/g/modstore/laravel-enum-js)
[![Total Downloads](https://img.shields.io/packagist/dt/modstore/laravel-enum-js.svg?style=flat-square)](https://packagist.org/packages/modstore/laravel-enum-js)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require modstore/laravel-enum-js
```

## Usage

Create a storage location, the path the generated files will be saved to.
``` php
// config/filesystems.php
...
'disks' => [
    'enum-js' => [
        'driver' => 'local',
        'root' => resource_path() . '/assets/js/enums',
    ],
],
...
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email mark@priceguard.com.au instead of using the issue tracker.

## Credits

- [Mark Whitney](https://github.com/modstore)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
