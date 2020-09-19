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
        return [];
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
