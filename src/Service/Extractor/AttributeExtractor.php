<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use ReflectionMethod;
use Reinfi\DependencyInjection\Annotation\AbstractAnnotation;
use Reinfi\DependencyInjection\Annotation\AbstractInjection;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Attribute\Inject as AttributeInject;
use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * Interface ExtractorInterface
 *
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
final class AttributeExtractor implements ExtractorInterface
{
    /**
     * @param string $className
     *
     * @return InjectionInterface[]
     */
    public function getPropertiesInjections(string $className): array
    {
        $injections = [];

        $reflection = new \ReflectionClass($className);

        foreach ($reflection->getProperties() as $index => $property) {
            $reflectionProperty = new \ReflectionProperty(
                $className,
                $property->getName()
            );

            $attributes = $reflectionProperty->getAttributes(
                AnnotationInterface::class,
                \ReflectionAttribute::IS_INSTANCEOF
            );

            $inject = isset($attributes[0]) ? $attributes[0]->newInstance() : null;

            if ($inject) {
                $injections[$index] = $inject;
            }
        }

        return $injections;
    }


    /**
     * @param string $className
     *
     * @return array
     */
    public function getConstructorInjections(string $className): array
    {
        if (!in_array('__construct', get_class_methods($className))) {
            return [];
        }

        $reflectionMethod = new ReflectionMethod($className, '__construct');

        return array_map(
            fn (\ReflectionAttribute $attribute): AnnotationInterface => $attribute->newInstance(),
            $reflectionMethod->getAttributes(AnnotationInterface::class, \ReflectionAttribute::IS_INSTANCEOF)
        );
    }
}
