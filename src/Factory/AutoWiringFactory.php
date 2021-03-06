<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Factory;

use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Service\AutoWiringService;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Factory
 */
final class AutoWiringFactory extends AbstractFactory
{
    /**
     * @inheritDoc
     */
    public function __invoke(
        \Interop\Container\ContainerInterface $container,
        $requestedName,
        array $options = null
    ) {
        $autoWiringService = $this->getAutoWiringService($container);

        $injections = $autoWiringService->resolveConstructorInjection(
            $container,
            $requestedName,
            $options
        );
        
        if ($injections === null) {
            return new $requestedName;
        }

        return $this->buildInstance($requestedName, $injections);
    }

    /**
     * @param ContainerInterface $container
     *
     * @return AutoWiringService
     */
    private function getAutoWiringService(ContainerInterface $container): AutoWiringService
    {
        if ($container instanceof AbstractPluginManager) {
            $container = $container->getServiceLocator();
        }
        
        return $container->get(AutoWiringService::class);
    }
}
