<?php

namespace Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\AbstractFactory\Config\InjectConfigAbstractFactory;
use Reinfi\DependencyInjection\Service\ConfigService;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\AbstractFactory\Config
 */
class InjectConfigAbstractFactoryTest extends TestCase
{
    use \Prophecy\PhpUnit\ProphecyTrait;
    /**
     * @test
     */
    public function itCanCreateServiceWithConfigPattern()
    {
        $factory = new InjectConfigAbstractFactory();

        /** @var ServiceLocatorInterface $container */
        $container = $this
            ->prophesize(ServiceLocatorInterface::class)
            ->reveal();

        $this->assertTrue(
            $factory->canCreateServiceWithName(
                $container,
                'config.reinfi.di.test',
                'Config.reinfi.di.test'
            ),
            'factory should be able to create service'
        );

        $this->assertTrue(
            $factory->canCreate(
                $container,
                'Config.reinfi.di.test'
            ),
            'factory should be able to create service'
        );
    }

    /**
     * @test
     */
    public function itCanNotCreateServiceWithNonConfigPattern()
    {
        $factory = new InjectConfigAbstractFactory();

        /** @var ServiceLocatorInterface $container */
        $container = $this
            ->prophesize(ServiceLocatorInterface::class)
            ->reveal();

        $this->assertFalse(
            $factory->canCreateServiceWithName(
                $container,
                'service.reinfi.di.test',
                'service.reinfi.di.test'
            ),
            'factory should not be able to create service'
        );

        $this->assertFalse(
            $factory->canCreate(
                $container,
                'service.reinfi.di.test'
            ),
            'factory should not be able to create service'
        );
    }

    /**
     * @test
     */
    public function itCallsConfigServiceForConfigPattern()
    {
        $factory = new InjectConfigAbstractFactory();

        $container = $this
            ->prophesize(ServiceLocatorInterface::class);

        $configService = $this->prophesize(ConfigService::class);
        $configService->resolve('reinfi.di.test')
            ->willReturn(true);

        $container->get(ConfigService::class)
            ->willReturn($configService->reveal());

        $factory->canCreateServiceWithName(
            $container->reveal(),
            'config.reinfi.di.test',
            'Config.reinfi.di.test'
        );

        $this->assertTrue(
            $factory->createServiceWithName(
                $container->reveal(),
                'config.reinfi.di.test',
                'Config.reinfi.di.test'
            )
        );
    }
}
