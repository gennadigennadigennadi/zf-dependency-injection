<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Http\Response;
use Laminas\Stdlib\ResponseInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResponseResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class ResponseResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForResponseInterface()
    {
        $resolver = new ResponseResolver();

        $parameter = new \ReflectionParameter(function (Response $response) {
        }, 'response');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForResponseClass()
    {
        $resolver = new ResponseResolver();

        $parameter = new ReflectionParameter(function (Response $response) {
        }, 'response');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForResponseInterfaceAsTypeHint()
    {
        $resolver = new ResponseResolver();

        $parameter  = new ReflectionParameter(function (ResponseInterface $response) {
        }, 'response');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoResponse()
    {
        $resolver = new ResponseResolver();

        $class = $this->prophesize(ReflectionClass::class);
        $class->getName()->willReturn('');
        $class->getInterfaceNames()->willReturn([]);

        $parameter = $this->prophesize(ReflectionParameter::class);
        $parameter->getClass()->willReturn($class->reveal());

        $parameter = new ReflectionParameter(function ($class) {
        }, 'class');

        $this->assertNull(
            $resolver->resolve($parameter),
            'return value should be null if not found'
        );
    }

    /**
     * @test
     */
    public function itReturnsNullIfParameterHasNoClass()
    {
        $resolver = new ResponseResolver();

        $parameter = new ReflectionParameter(function ($classWithoutType) {
        }, 'classWithoutType');

        $this->assertNull(
            $resolver->resolve($parameter),
            'return value should be null if not found'
        );
    }
}
