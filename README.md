## Installation

Install via composer

``` bash
$ composer require lifeonscreen/nova-google2fa
```

Publish config and migrations

``` bash
$ php artisan vendor:publish --provider="Lifeonscreen\Google2fa\ToolServiceProvider"
```

Run migrations

``` bash
$ php artisan migrate
```

Add relation to User model
```php
use Lifeonscreen\Google2fa\Models\User2fa;

...

/**
 * @return HasOne
 */
public function user2fa(): HasOne
{
    return $this->hasOne(User2fa::class);
}
```

Add middleware to `nova.config`.
```php
[
    ...
    'middleware' => [
        ...
        \Lifeonscreen\Google2fa\Http\Middleware\Google2fa::class,
        ...
    ],
]
```

## Security

If you discover any security-related issues, please email the author instead of using the issue tracker.
## Credits 
- [Jani Cerar](https://github.com/janicerar)

## License

MIT license. Please see the [license file](docs/license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/lifeonscreen/nova-google2fa.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/lifeonscreen/nova-google2fa.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/lifeonscreen/nova-google2fa
[link-downloads]: https://packagist.org/packages/lifeonscreen/nova-google2fa
[link-author]: https://github.com/LifeOnScreen