<?php

namespace Reinfi\DependencyInjection\Controller;

use Reinfi\DependencyInjection\Factory\AutoWiringFactory;
use Reinfi\DependencyInjection\Factory\InjectionFactory;
use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\CacheKeyTrait;
use Zend\Cache\Storage\StorageInterface;
use Zend\Mvc\Controller\AbstractConsoleController;

/**
 * @codeCoverageIgnore
 *
 * @package Reinfi\DependencyInjection\Controller
 */
class CacheWarmupController extends AbstractConsoleController
{
    use CacheKeyTrait;

    /**
     * @var array
     */
    private $serviceManagerConfig;

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var ResolverService
     */
    private $resolverService;

    /**
     * @var StorageInterface
     */
    private $cache;

    /**
     * @param array              $serviceManagerConfig
     * @param ExtractorInterface $extractor
     * @param ResolverService    $resolverService
     * @param StorageInterface   $cache
     */
    public function __construct(
        array $serviceManagerConfig,
        ExtractorInterface $extractor,
        ResolverService $resolverService,
        StorageInterface $cache
    ) {
        $this->serviceManagerConfig = $serviceManagerConfig;
        $this->extractor = $extractor;
        $this->cache = $cache;
        $this->resolverService = $resolverService;
    }

    /**
     *
     */
    public function indexAction()
    {
        $factoriesConfig = $this->serviceManagerConfig['factories'];

        foreach ($factoriesConfig as $className => $factoryClass) {
            if ($factoryClass === InjectionFactory::class) {
                $injections = array_merge(
                    $this->extractor->getPropertiesInjections($className),
                    $this->extractor->getConstructorInjections($className)
                );

                $this->cache->setItem(
                    $this->buildCacheKey($className),
                    $injections
                );

                continue;
            }

            if ($factoryClass === AutoWiringFactory::class) {
                $injections = $this->resolverService->resolve($className);

                $this->cache->setItem(
                    $this->buildCacheKey($className),
                    $injections
                );

                continue;
            }
        }

        $this->console->writeLine('Finished cache warmup');
    }
}
