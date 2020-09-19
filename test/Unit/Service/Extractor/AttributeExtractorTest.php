<?php

declare(strict_types=1);

namespace Reinfi\DependencyInjection\Test\Unit\Service\Extractor;

use PHPUnit\Framework\TestCase;
use Reinfi\DependencyInjection\Annotation\Inject;
use Reinfi\DependencyInjection\Service\Extractor\AttributeExtractor;
use Reinfi\DependencyInjection\Test\Service\ServiceAttributeConstructor;

final class AttributeExtractorTest extends TestCase
{
    public function testThatItReadsAttributes(): void
    {
        $extractor  = new AttributeExtractor();

        /** @var AnnotationInject[] */
        $injections = $extractor->getConstructorInjections(ServiceAttributeConstructor::class);

        $this->assertNotEmpty($injections);
        $this->assertContainsOnlyInstancesOf(Inject::class, $injections);
    }
}
