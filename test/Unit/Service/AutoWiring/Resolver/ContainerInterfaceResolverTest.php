<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Laminas\ServiceManager\AbstractPluginManager;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ContainerInterfaceResolverTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsInjectionInterfaceIfIsInterfaceTypeHint()
    {
        $resolver = new ContainerInterfaceResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->isInterface()->willReturn(true);
        $class->getName()->willReturn(ContainerInterface::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceIfHasInterfaceImplemented()
    {
        $resolver = new ContainerInterfaceResolver();

        $class = $this->prophesize(\ReflectionClass::class);
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([ContainerInterface::class]);
        $class->getName()->willReturn(ServiceLocatorInterface::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfIsAbstractPluginManager()
    {
        $resolver = new ContainerInterfaceResolver();
        $class = $this->prophesize(\ReflectionClass::class);
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([]);
        $class->getName()->willReturn(AbstractPluginManager::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
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

        $class = $this->prophesize(\ReflectionClass::class);
        $class->isInterface()->willReturn(false);
        $class->getInterfaceNames()->willReturn([]);
        $class->getName()->willReturn(ContainerInterface::class);
        $parameter = $this->prophesize(\ReflectionParameter::class);
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

        $parameter = $this->prophesize(\ReflectionParameter::class);
        $parameter->getClass()->willReturn(null);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection);
    }
}
