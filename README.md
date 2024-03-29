# Laravel Enum Js

Package to generate javascript versions of your PHP enum files to be used in your js builds etc.
Works with Php8.1+ enums, constants in your PHP files, or you can use a package such as https://github.com/BenSampo/laravel-enum

## Installation

You can install the package via composer:

```bash
composer require modstore/laravel-enum-js
```

Publish the config:

```
php artisan vendor:publish --provider="Modstore\LaravelEnumJs\LaravelEnumJsServiceProvider"
```

## Usage

Create a storage location, the path the generated files will be saved to.
``` php
// config/filesystems.php
...
'disks' => [
    'enum-js' => [
        'driver' => 'local',
        'root' => resource_path() . '/js/enums',
    ],
],
...
```
Check the other configuration options in the config file.

You can generate the js files by running the following Artisan command:
``` bash
php artisan enum-js:generate 
```

NOTE: When developing, if you create a new file, you will likely need to dump the
 autoload files before this command will pick it up, e.g.
``` bash
composer dump-autoload
php artisan enum-js:generate 
```

You can then use the generated files in your javascript like so:
``` javascript
import * as Status from '../enums/Status'

if (this.status === Status.Active) {
    // Do something.
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mark Whitney](https://github.com/modstore)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
