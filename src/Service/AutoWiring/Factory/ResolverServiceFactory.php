<?php

namespace Reinfi\DependencyInjection\Service\AutoWiring\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Config\ModuleConfig;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerInterfaceResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ContainerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\PluginManagerResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\RequestResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\Resolver\ResponseResolver;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;

/**
 * @package Reinfi\DependencyInjection\Service\AutoWiring\Factory
 */
class ResolverServiceFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return ResolverService
     */
    public function __invoke(ContainerInterface $container): ResolverService
    {
        /** @var array $config */
        $config = $container->get(ModuleConfig::class);

        $resolverStackConfig = $this->getResolverStack($config);

        $resolverStack = array_map(
            [$container, 'get'],
            $resolverStackConfig
        );

        return new ResolverService($resolverStack);
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function getResolverStack(array $config): array
    {
        $defaultResolverStackConfig = [
            ContainerResolver::class,
            PluginManagerResolver::class,
            ContainerInterfaceResolver::class,
            RequestResolver::class,
            ResponseResolver::class,
        ];

        $resolverStackConfig = $config['autowire_resolver'] ?? null;
        if ($resolverStackConfig === null) {
            return $defaultResolverStackConfig;
        }

        return array_merge(
            $defaultResolverStackConfig,
            $resolverStackConfig
        );
    }
}
