<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Command;

use Reinfi\DependencyInjection\Service\AutoWiring\ResolverService;
use Reinfi\DependencyInjection\Service\CacheService;
use Reinfi\DependencyInjection\Service\Extractor\ExtractorInterface;
use Reinfi\DependencyInjection\Traits\WarmupTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Mvc\Application;

/**
 * @package Reinfi\DependencyInjection\Command
 */
class CacheWarmupCommand extends Command
{
    use WarmupTrait;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('reinfi:di:cache')
            ->setDescription('Warm up the cache');

        $this
            ->addArgument(
                'applicationConfig',
                InputArgument::REQUIRED,
                'Path to application config which includes services'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start up application with supplied config...');

        $config = $input->getArgument('applicationConfig');
        $path = stream_resolve_include_path($config);
        if ($path === false || !is_readable($path)) {
            throw new \InvalidArgumentException("Invalid loader path: {$config}");
        }

        $container = Application::init(include $path)
            ->getServiceManager();

        $serviceManagerConfig = ($container->get('config'))['service_manager'];

        $this->warmupConfig(
            $serviceManagerConfig['factories'] ?? [],
            $container->get(ExtractorInterface::class),
            $container->get(ResolverService::class),
            $container->get(CacheService::class)
        );

        $output->writeln('Finished cache warmup');
    }
}
