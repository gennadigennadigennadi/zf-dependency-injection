<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Attribute;
use InvalidArgumentException;
use Psr\Container\ContainerInterface;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class Inject implements AnnotationInterface
{
    /**
     * @var string
     */
    public $value;

    public function __construct($class = null)
    {
        if (null === $class) {
            return;
        }

        if (\is_array($class)) {
            $this->value = $class['value'];

            return;
        }

        if (\is_string($class)) {
            $this->value = $class;

            return;
        }

        throw new InvalidArgumentException();
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        return $container->get($this->value);
    }
}
