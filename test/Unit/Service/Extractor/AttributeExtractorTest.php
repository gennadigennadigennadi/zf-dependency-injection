<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Reinfi\DependencyInjection\Annotation\AnnotationInterface;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Service\Extractor\AnnotationExtractor;
use Reinfi\DependencyInjection\Service\Extractor\AttributeExtractor;
use Reinfi\DependencyInjection\Test\Service\ServiceAnnotation;
use Reinfi\DependencyInjection\Test\Service\ServiceAttributeConstructor;

final class AttributeExtractorTest extends TestCase
{
    /**
     * @test
     */
    public function itResolvesPropertyAnnotations()
    {
        $extractor  = new AttributeExtractor();

        $injections = $extractor->getPropertiesInjections(ServiceAttributeConstructor::class);

        $this->assertCount(1, $injections);
        $this->assertContainsOnlyInstancesOf(AnnotationInterface::class, $injections);
    }

    public function testThatItReadsAttributes(): void
    {
        $extractor  = new AttributeExtractor();

        /** @var AnnotationInject[] */
        $injections = $extractor->getConstructorInjections(ServiceAttributeConstructor::class);

        $this->assertNotEmpty($injections);
        $this->assertContainsOnlyInstancesOf(Inject::class, $injections);
    }
}
