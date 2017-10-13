<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Resolver;
use ReflectionParameter;
use Reinfi\DependencyInjection\Injection\AutoWiring;
use Zend\Stdlib\ResponseInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Resolver
 */
class ResponseResolver implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(ReflectionParameter $parameter)
    {
        if ($parameter->getClass() === null) {
            return null;
        }

        $reflectionClass = $parameter->getClass();
        $interfaceNames = $reflectionClass->getInterfaceNames();

        if (!in_array(ResponseInterface::class, $interfaceNames)) {
            return null;
        }

        return new AutoWiring('Response');
    }
}