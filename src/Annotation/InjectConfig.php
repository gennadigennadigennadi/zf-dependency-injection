<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Annotation;

use Attribute;
use Laminas\Config\Config;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\ConfigService;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_METHOD)]

/**
 * @package Reinfi\DependencyInjection\Annotation
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD"})
 */
final class InjectConfig extends AbstractAnnotation
{
    /**
     * @var string
     */
    private $configPath;

    /**
     * @var bool
     */
    private $asArray = false;

    /**
     * @param array $values
     */
    public function __construct(array $values = [], string $configPath = '', bool $asArray = false)
    {
        $this->asArray = (bool) ($values['asArray'] ?? $asArray);
        $this->configPath = $values['value'] ?? $configPath;
    }
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container)
    {
        $container = $this->determineContainer($container);

        $resolvedConfig = $container->get(ConfigService::class)->resolve($this->configPath);

        if ($this->asArray && $resolvedConfig instanceof Config) {
            return $resolvedConfig->toArray();
        }

        return $resolvedConfig;
    }
}
