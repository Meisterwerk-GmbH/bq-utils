<?php

declare(strict_types=1);

namespace Meisterwerk\BqUtils\Tests\Consumer;

use Meisterwerk\BqUtils\Consumer\OrderEventConsumer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class OrderEventConsumerTest extends TestCase
{
    #[DataProvider('capturedEvents')]
    public function testConsumesCapturedEvent(string $filename): void
    {
        $this->expectNotToPerformAssertions();

        $payload = file_get_contents(dirname(__DIR__) . '/Fixtures/' . $filename);

        (new OrderEventConsumer($this->createSymfonyFrameworkLikeSerializer()))->consume($payload);
    }

    #[DataProvider('capturedEvents')]
    public function testReadsPropertiesFromCapturedEvent(string $filename): void
    {
        $payload = file_get_contents(dirname(__DIR__) . '/Fixtures/' . $filename);
        $event = (new OrderEventConsumer($this->createSymfonyFrameworkLikeSerializer()))->consume($payload);

        self::assertSame('Deutsch', $event->order->getPropertyValue('sprache'));
        self::assertNull($event->order->getPropertyValue('unknown_property'));
    }

    public static function capturedEvents(): iterable
    {
        yield 'started' => ['order-started.json'];
        yield 'canceled' => ['order-canceled.json'];
        yield 'updated' => ['order-updated.json'];
    }

    private function createSymfonyFrameworkLikeSerializer(): Serializer
    {
        $metadata = new ClassMetadataFactory(new AttributeLoader());
        return new Serializer([
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new ObjectNormalizer(
                classMetadataFactory: $metadata,
                nameConverter: new MetadataAwareNameConverter($metadata),
                propertyTypeExtractor: new PropertyInfoExtractor(typeExtractors: [
                    new PhpDocExtractor(),
                    new ReflectionExtractor(),
                ]),
            ),
        ], [new JsonEncoder()]);
    }
}
