<?php

namespace Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver;

use Laminas\Http\Request;
use Laminas\Stdlib\RequestInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use ReflectionClass;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Service\AutoWiring\Resolver
 */
class RequestResolverTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestInterface()
    {
        $resolver = new RequestResolver();

        $parameter = new \ReflectionParameter(function (RequestInterface $request) {
        }, 'request');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestClass()
    {
        $resolver = new RequestResolver();

        $parameter = new \ReflectionParameter(function (Request $request) {
        }, 'request');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsInjectionInterfaceForRequestInterfaceAsTypehint()
    {
        $resolver = new RequestResolver();

        $parameter = new \ReflectionParameter(function (RequestInterface $request) {
        }, 'request');

        $injection = $resolver->resolve($parameter);

        $this->assertInstanceOf(AutoWiring::class, $injection);
    }

    /**
     * @test
     */
    public function itReturnsNullIfNoRequest()
    {
        $resolver = new RequestResolver();

        $parameter = new ReflectionParameter(function (\stdClass $noRequest) {
        }, 'noRequest');
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
        $resolver = new RequestResolver();

        $parameter = new ReflectionParameter(function ($noClass) {
        }, 'noClass');

        $this->assertNull(
            $resolver->resolve($parameter),
            'return value should be null if not found'
        );
    }
}
