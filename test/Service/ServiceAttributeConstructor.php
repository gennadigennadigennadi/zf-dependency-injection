<?php

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\Inject;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceAttributeConstructor
{
    #[Inject(Service2::class)]
    private Service2 $service2;

    #[Inject(Service1::class)]
    private Service1 $service1;

    public function __construct(Service2 $service2, Service1 $service1)
    {
        $this->service2 = $service2;
    }

    public function getService2(): Service2
    {
        return $this->service2;
    }
}
