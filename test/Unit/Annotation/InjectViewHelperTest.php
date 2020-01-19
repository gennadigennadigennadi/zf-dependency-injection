<?php

namespace Reinfi\DependencyInjection\Test\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Reinfi\DependencyInjection\Annotation\InjectViewHelper;
use Reinfi\DependencyInjection\Test\Service\Service1;
use Laminas\ServiceManager\AbstractPluginManager;

/**
 * @package Reinfi\DependencyInjection\Test\Unit\Annotation
 */
class InjectViewHelperTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider getAnnotationValues
     *
     * @param array  $values
     * @param string $className
     */
    public function itCallsPluginManagerWithValue(
        array $values,
        string $className
    ) {
        $inject = new InjectViewHelper($values);

        $pluginManager = $this->prophesize(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $pluginManager->get($className, $values['options'])
                ->willReturn(true);
        } else {
            $pluginManager->get($className)
                ->willReturn(true);
        }

        $container = $this->prophesize(ContainerInterface::class);
        $container->get('ViewHelperManager')
            ->willReturn($pluginManager->reveal());

        $this->assertTrue(
            $inject($container->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @test
     *
     * @dataProvider getAnnotationValues
     *
     * @param array  $values
     * @param string $className
     */
    public function itCallsPluginManagerFromParentServiceLocator(
        array $values,
        string $className
    ) {
        $inject = new InjectViewHelper($values);

        $filterManager = $this->prophesize(AbstractPluginManager::class);

        if (isset($values['options'])) {
            $filterManager->get($className, $values['options'])
                ->willReturn(true);
        } else {
            $filterManager->get($className)
                ->willReturn(true);
        }

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('ViewHelperManager')
            ->willReturn($filterManager->reveal());

        $pluginManager = $this->prophesize(AbstractPluginManager::class);
        $pluginManager->getServiceLocator()
            ->willReturn($container->reveal());

        $this->assertTrue(
            $inject($pluginManager->reveal()),
            'Invoke should return true'
        );
    }

    /**
     * @return array
     */
    public function getAnnotationValues(): array
    {
        return [
            [
                [
                    'value' => Service1::class,
                ],
                Service1::class,
            ],
            [
                [
                    'name'    => Service1::class,
                    'options' => [ 'field' => true ],
                ],
                Service1::class,
            ],
            [
                [
                    'name' => Service1::class,
                ],
                Service1::class,
            ],
        ];
    }
}
