<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use ReflectionMethod;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;

/**
 * @package Reinfi\DependencyInjection\Service\Extractor
 */
class AnnotationExtractor implements ExtractorInterface
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * @param AnnotationReader $reader
     */
    public function __construct(AnnotationReader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @inheritdoc
     */
    public function getPropertiesInjections(string $className): array
    {
        $injections = [];
        $reflection = new \ReflectionClass($className);

        foreach ($reflection->getProperties() as $index => $property) {
            $inject = null;
            $reflectionProperty = new \ReflectionProperty(
                $className,
                $property->getName()
            );

            if (\PHP_VERSION_ID >= 80000) {
                $attributes = $reflectionProperty->getAttributes(
                    AnnotationInterface::class,
                    \ReflectionAttribute::IS_INSTANCEOF
                );

                $inject = isset($attributes[0]) ? $attributes[0]->newInstance() : null;
            }
            
            $inject = $inject ?? $this->reader->getPropertyAnnotation(
                $reflectionProperty,
                AnnotationInterface::class
            );

            if (null !== $inject) {
                $injections[$index] = $inject;
            }
        }

        return $injections;
    }

    /**
     * @inheritDoc
     */
    public function getConstructorInjections(string $className): array
    {
        if (!in_array('__construct', get_class_methods($className))) {
            return [];
        }

        $reflectionMethod = new ReflectionMethod($className, '__construct');

        $attributes = [];

        if (\PHP_VERSION_ID >= 80000) {
            $attributes = array_map(
                fn ($attribute) => $attribute->newInstance(),
                $reflectionMethod->getAttributes(
                    AnnotationInterface::class,
                    \ReflectionAttribute::IS_INSTANCEOF
                )
            );
        }

        $annotations = $this->reader->getMethodAnnotations($reflectionMethod);

        $injections = array_filter(
            array_merge($annotations, $attributes),
            function ($annotation) {
                return $annotation instanceof AnnotationInterface;
            }
        );

        return $injections;
    }
}
