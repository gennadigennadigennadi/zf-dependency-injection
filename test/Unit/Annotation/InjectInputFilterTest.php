<?php

namespace Reinfi\DependencyInjection\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectInputFilter;
use Reinfi\DependencyInjection\Service\InjectionService;
use Zend\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Unit\Annotation
 */
class InjectInputFilterTest extends TestCase
{
    /**
     * @test
     */
    public function itCallsPluginManagerWithValue()
    {
        $inject = new InjectInputFilter();

        $inject->value = InjectionService::class;

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->get(InjectionService::class)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('InputFilterManager')
            ->willReturn($pluginManager->reveal());

        $this->assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     */
    public function itCallsPluginManagerFromParentServiceLocator()
    {
        $inject = new InjectInputFilter();

        $inject->value = InjectionService::class;

        $filterManager = $this->prophesize(AbstractPluginManager::class);
        $filterManager->get(InjectionService::class)
            ->willReturn(true);

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('InputFilterManager')
            ->willReturn($filterManager->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $this->assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }
}