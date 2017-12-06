Flusher
=======

*By [endroid](https://endroid.nl/)*

[![Latest Stable Version](http://img.shields.io/packagist/v/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)
[![Build Status](http://img.shields.io/travis/endroid/Flusher.svg)](http://travis-ci.org/endroid/Flusher)
[![Total Downloads](http://img.shields.io/packagist/dt/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)
[![Monthly Downloads](http://img.shields.io/packagist/dm/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)
[![License](http://img.shields.io/packagist/l/endroid/flusher.svg)](https://packagist.org/packages/endroid/flusher)

When you import or modify large amounts of data it is often necessary to define
the optimal batch size before flushing: small batch sizes perform bad because of
the overhead in each flush. And batch sizes that are too large perform bad because
of the high memory usage and the need to calculate a large change set. Also the
batch size you choose can give different results on different types of hardware.

This library helps you write entities to the database without worrying about the
batch size. It incrementally tries new batch sizes (given a step size), sticks
with the one that gives the highest performance or switches to a better batch size
if the circumstances have changed.

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
$ composer require endroid/flusher
```

## Usage

In order to enable auto flushing you first need to create a Flusher for the
entity manager you are currently using.

```php
$flusher = new Flusher($manager);
```

Then when you performed operations on your entity manager you can call the
flush() method on the flusher any time to notify there are changes.

```php
for ($n = 1; $n <= 50000; $n++) {
    $task = new Task();
    $task->setName('Task '.$n);
    $manager->persist($task);
    $flusher->flush();
}
```

Because there is no way of knowing if there are pending flushes at the end you
need to call finish() to make sure all data is flushed.

```php
$flusher->finish();
```

## Symfony integration

The [endroid/flusher-bundle](https://github.com/endroid/EndroidFlusherBundle)
integrates this library in Symfony and provides the following features.

* Optional replacement your default entity manager with the FlusherEntityManager
* Configuration of the step size used when determining the optimal batch size
* Injection of the Flusher or the FlusherEntityManager anywhere in your application

## Versioning

Version numbers follow the MAJOR.MINOR.PATCH scheme. Backwards compatibility
breaking changes will be kept to a minimum but be aware that these can occur.
Lock your dependencies for production and test your code when upgrading.

## License

This bundle is under the MIT license. For the full copyright and license
information please view the LICENSE file that was distributed with this source code.
