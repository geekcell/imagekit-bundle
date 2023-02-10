<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Tests\Unit\Imagekit;

use GeekCell\ImagekitBundle\Contracts\Provider;
use GeekCell\ImagekitBundle\Imagekit\ProviderRegistry;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProviderRegistryTest extends TestCase
{
    public function testRegistry(): void
    {
        // Given
        $registry = new ProviderRegistry();

        $fooProvider = Mockery::mock(Provider::class);
        $barProvider = Mockery::mock(Provider::class);
        $bazProvider = Mockery::mock(Provider::class);

        // When
        $registry->register('foo', $fooProvider);
        $registry->register('bar', $barProvider);
        $registry->register('baz', $bazProvider);

        $this->expectException(\InvalidArgumentException::class);

        // Then
        $this->assertSame($fooProvider, $registry->getProvider('foo'));
        $this->assertSame($barProvider, $registry->getProvider('bar'));
        $this->assertSame($bazProvider, $registry->getProvider('baz'));

        $registry->getProvider('invalid');
    }
}
