<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Attribute;
use Psr\Container\ContainerInterface;

#[Attribute]
final class Inject implements AnnotationInterface
{
    /**
     * @var string
     */
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        return $container->get($this->value);
    }
}
