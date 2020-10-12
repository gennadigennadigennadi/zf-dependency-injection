<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceLocatorInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceIfIsInterfaceTypeHint()
    {
        $resolver = new ContainerInterfaceResolver();

        $parameter = new ReflectionParameter(function (ContainerInterface $container) {
        }, 'container');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceIfHasInterfaceImplemented()
    {
        $resolver = new ContainerInterfaceResolver();

        $parameter = new ReflectionParameter(function (ServiceLocatorInterface $serviceLocator) {
        }, 'serviceLocator');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfIsAbstractPluginManager()
    {
        $resolver = new ContainerInterfaceResolver();
        $class = $this->prophesize(ReflectionClass::class);
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([]);
        $class->getName()->willReturn(AbstractPluginManager::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfOtherClass()
    {
        $resolver = new ContainerInterfaceResolver();

        $class = $this->prophesize(ReflectionClass::class);
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([]);
        $class->getName()->willReturn(ContainerInterface::class);
        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoClass()
    {
        $resolver = new ContainerInterfaceResolver();

        $parameter = new ReflectionParameter(
            function ($noClass) {
            },
            'noClass'
        );


        $injection = $resolver->resolve($parameter);

        $this->assertNull($injection);
    }
}
