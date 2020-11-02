<?php

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Annotation\InjectConfig;
use Reinfi\DependencyInjection\Test\Service\Service2;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceAttribute
{
    #[Inject(Service2::class)]
    protected $service2;

    #[InjectConfig(configPath: 'test.value')]
    protected $value;

    #[InjectConfig(configPath: 'test', asArray: true)]
    protected $valueAsArray;

    public function __construct(Service2 $service2, int $value, array $valueAsArray)
    {
        $this->service2 = $service2;
        $this->value = $value;
        $this->valueAsArray = $valueAsArray;
    }
}
