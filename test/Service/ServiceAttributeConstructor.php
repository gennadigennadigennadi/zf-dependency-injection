<?php

namespace Reinfi\DependencyInjection\Test\Service;

use Reinfi\DependencyInjection\Annotation\Inject;

/**
 * @package Reinfi\DependencyInjection\Test\Service
 */
class ServiceAttributeConstructor
{
    private Service2 $service2;

    #[Inject(Service2::class)]
    public function __construct(Service2 $service2)
    {
        $this->service2 = $service2;
    }
}
