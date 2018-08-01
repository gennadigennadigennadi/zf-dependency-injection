<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\AutoWiring;

use ReflectionClass;
use Reinfi\DependencyInjection\Exception\AutoWiringNotPossibleException;
use Reinfi\DependencyInjection\Injection\InjectionInterface;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResolverInterface;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring
 */
class ResolverService implements ResolverServiceInterface
{
    /**
     * @var ResolverInterface[]
     */
    private $resolverStack;

    /**
     * @param ResolverInterface[] $resolverStack
     */
    public function __construct(
        array $resolverStack
    ) {
        $this->resolverStack = $resolverStack;
    }

    /**
     * @param string $className
     *
     * @return InjectionInterface[]
     */
    public function resolve(string $className): array
    {
        $reflClass = new ReflectionClass($className);

        $constructor = $reflClass->getConstructor();

        if ($constructor === null) {
            return [];
        }

        $parameters = $constructor->getParameters();

        return array_map(function(\ReflectionParameter $parameter) use ($reflClass) {
            return $this->resolveParameter($reflClass, $parameter);
        }, $parameters);
    }

    /**
     * @param \ReflectionParameter $parameter
     *
     * @return InjectionInterface
     * @throws AutoWiringNotPossibleException
     */
    private function resolveParameter(
        ReflectionClass $constructedClass,
        \ReflectionParameter $parameter
    ): InjectionInterface {
        foreach ($this->resolverStack as $resolver) {
            $injection = $resolver->resolve($parameter);

            if ($injection instanceof InjectionInterface) {
                return $injection;
            }
        }

        if (!$parameter->hasType()) {
            throw AutoWiringNotPossibleException::fromMissingTypeHint($parameter, $constructedClass);
        }

        if ($parameter->getType()->isBuiltin()) {
            throw AutoWiringNotPossibleException::fromBuildInType($parameter, $constructedClass);
        }


        throw AutoWiringNotPossibleException::fromClassName($parameter->getClass(), $constructedClass);
    }
}
