<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Reinfi\DependencyInjection\Injection\InjectionInterface;

/**
 * @package Reinfi\DependencyInjection\Annotation
 */
#[Attribute]
interface AnnotationInterface extends InjectionInterface
{
}
