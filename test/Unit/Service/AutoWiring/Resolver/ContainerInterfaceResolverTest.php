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
use stdClass;

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

        $parameter = new ReflectionParameter(function (AbstractPluginManager $class) {
        }, 'class');
        $injection = $resolver->resolve($parameter);

        $this->assertNull($injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfOtherClass()
    {
        $resolver = new ContainerInterfaceResolver();

        $parameter = new ReflectionParameter(
            function (stdClass $otherClass) {
            },
            'otherClass'
        );

        $injection = $resolver->resolve($parameter);

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
