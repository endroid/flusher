Flusher
=======

*By [endroid](http://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)
[![Build Status](http://img.shields.io/travis/endroid/Flusher.svg)](http://travis-ci.org/endroid/Flusher)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)
[![License](http://img.shields.io/packagist/l/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)

This library helps you write entities to the database without worrying when to flush.

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require endroid/flush
```

## Symfony integration

Register the Symfony bundle in the kernel.

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Endroid\Flusher\Bundle\FlusherBundle\EndroidFlusherBundle(),
    ];
}
```

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
