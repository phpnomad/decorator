# phpnomad/decorator

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/decorator.svg)](https://packagist.org/packages/phpnomad/decorator)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/decorator.svg)](https://packagist.org/packages/phpnomad/decorator)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/decorator.svg)](https://packagist.org/packages/phpnomad/decorator)
[![License](https://img.shields.io/packagist/l/phpnomad/decorator.svg)](https://packagist.org/packages/phpnomad/decorator)

`phpnomad/decorator` is a small helper for writing decorator classes in PHP. It ships a single trait, `WithDecoratedInstance`, that forwards method calls to a wrapped object through `__call()` using an explicit allowlist, so a decorator only has to implement the behavior it actually changes.

## Installation

```bash
composer require phpnomad/decorator
```

## Quick Start

Add the trait to a class that wraps another instance, assign the wrapped object, and list the methods you want forwarded.

```php
<?php

use PHPNomad\Decorator\Traits\WithDecoratedInstance;

class CachingRepository
{
    use WithDecoratedInstance;

    protected array $allowedMethods = ['find', 'all'];

    public function __construct(Repository $repository)
    {
        $this->decoratedInstance = $repository;
    }

    public function save(Model $model): void
    {
        // Custom behavior here, then delegate if needed.
        $this->decoratedInstance->save($model);
    }
}
```

Calls to `find()` or `all()` on a `CachingRepository` are forwarded to the wrapped `Repository`. Calls to any method that is not on `$allowedMethods` and is not defined on the decorator itself raise an `E_USER_ERROR`.

## Overview

The package exports one trait, `PHPNomad\Decorator\Traits\WithDecoratedInstance`. Mixing it into a class adds four things.

- A `$decoratedInstance` property that holds the object being wrapped.
- An `$allowedMethods` array so that method forwarding is opt-in rather than automatic.
- A `__call()` implementation that routes allowlisted method calls to `$decoratedInstance` via `call_user_func_array`.
- An explicit `E_USER_ERROR` for any other inaccessible method call, so typos and unintended forwards fail loudly instead of silently.

Because the allowlist is a plain array property, you control exactly which parts of the wrapped object's surface leak through the decorator.

## Documentation

Broader PHPNomad framework documentation lives at [phpnomad.com](https://phpnomad.com).

## License

Released under the [MIT License](LICENSE.txt).
