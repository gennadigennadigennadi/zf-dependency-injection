<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionParameter;
use ReflectionType;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\BuildInTypeWithDefaultResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class BuildInTypeWithDefaultResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterface()
    {
        $reflectionParameter = new ReflectionParameter(
            function (int $isBuiltin = 0) {
            },
            'isBuiltin'
        );

        $resolver = new BuildInTypeWithDefaultResolver();

        $injection = $resolver->resolve($reflectionParameter);

        $this->assertInstanceOf(InjectionInterface::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoType()
    {
        $resolver = new BuildInTypeWithDefaultResolver();

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->hasType()->willReturn(false);

        $injection = $resolver->resolve($parameter->reveal());

        $this->assertNull($injection, 'Should be null if parameter has no type');
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoBuildInType()
    {
        $reflectionParameter = new ReflectionParameter(
            function (NoBuildInType $noBuildInType) {},
            'noBuildInType'
        );

        $resolver = new BuildInTypeWithDefaultResolver();

        $injection = $resolver->resolve($reflectionParameter);

        $this->assertNull($injection, 'Should be null if parameter is not a buildin type');
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoDefaultValueAvailable()
    {
        $reflectionParameter = new ReflectionParameter(
            function (string $noDefault) {},
            'noDefault'
        );

        $resolver = new BuildInTypeWithDefaultResolver();

        $injection = $resolver->resolve($reflectionParameter);

        $this->assertNull($injection, 'Should be null if parameter has no default value');
    }
}
