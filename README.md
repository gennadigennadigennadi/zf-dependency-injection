Configure dependency injection in Zend Framework 2 using annotations.

Heavily inspired by https://github.com/mikemix/mxdiModule.

1. [Installation](#installation)
2. [How to use it](#howtouseit)
3. [Caching](#caching)
4. [Console commands](#console-commands)

### Installation

1. Install with Composer: `composer require reinfi/zf-dependency-inject`.

2. Enable the module via ZF2 config in `appliation.config.php` under `modules` key:

    ```php
    return [
        //
        //
        'modules' => [
            'Reinfi\DependencyInjection',
            // other modules
        ],
        //
        //
    ];
    ```
### How to use it

You need to register your services under the factories key within the service manager
```
'service_manager' => [
    'factories' => [
        YourService::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
    ],
]
```

Then you can add annotations to your classes.

Following annotations are supported:

* Inject (directly injects a service from the service locator)
* InjectParent (must be used if you inject a service from a plugin manager)
* InjectConfig (dot separated path to a config value, e.g. service_manager.factories)

Also in addition there a several annotations to inject from plugin managers.
* InjectViewHelper
* InjectFilter
* InjectInputFilter
* InjectValidator
* InjectHydrator

* InjectDoctrineRepository

### Caching

Parsing mapping sources is very heavy. You *should* enable the cache on production servers.

You can set up caching easily with any custom or pre-existing ZF2 cache adapter.

```
'reinfi.dependencyInjection' => [
    'cache' => \Zend\Cache\Storage\Adapter\Memory:class,
    'cache_options => [],
]
```

You can find more information about available out-of-the-box adapters at the [ZF2 docs site](http://framework.zend.com/manual/current/en/modules/zend.cache.storage.adapter.html).

### Console commands

* Warmup cache: `php public/index.php reinfi:di cache warmup`

  Fills the cache with every injection required by a class.

### Additional Notes

There is a second way to add injections. You can specify a injection list within the service_manager configuration.

```
'service_manager' => [
    'factories' => [
        YourService::class => \Reinfi\DependencyInjection\Factory\InjectionFactory::class,
    ],
    'injections' => [
        YourClass:class => [
            YourDependencyClass::class,
        ],
    ],
]
```