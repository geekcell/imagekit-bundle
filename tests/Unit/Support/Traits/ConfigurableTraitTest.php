<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Tests\Unit\Support\Traits;

use GeekCell\ImagekitBundle\Support\Traits\ConfigurableTrait;
use PHPUnit\Framework\TestCase;

class ConfigurableTraitTest extends TestCase
{
    use ConfigurableTrait;

    public function testConfigure(): void
    {
        // Given - When
        $this->configure();

        // Then
        $this->assertIsArray($this->config);
        $this->assertEmpty($this->config);
    }

    public function testPath(): void
    {
        // Given
        $this->configure();

        // When
        $this->path('path');

        // Then
        $this->assertIsArray($this->config);
        $this->assertArrayHasKey('path', $this->config);
        $this->assertEquals('path', $this->config['path'] ?? '');
    }

    public function testSourceUrl(): void
    {
        // Given
        $this->configure();

        // When
        $this->sourceUrl('sourceUrl');

        // Then
        $this->assertIsArray($this->config);
        $this->assertArrayHasKey('src', $this->config);
        $this->assertEquals('sourceUrl', $this->config['src'] ?? '');
    }

    public function testTransform(): void
    {
        // Given
        $this->configure();

        // When
        $this->transform('key', 'value');

        // Then
        $this->assertIsArray($this->config);
        $this->assertArrayHasKey('transformation', $this->config);
        $this->assertIsArray($this->config['transformation'] ?? null);

        $transformations = $this->config['transformation'];
        $this->assertNotEmpty($transformations);
        $this->assertArrayHasKey('key', $transformations[0]);
        $this->assertEquals('value', $transformations[0]['key']);
    }

    public function testSigned(): void
    {
        // Given
        $this->configure();

        // When
        $this->signed();

        // Then
        $this->assertIsArray($this->config);
        $this->assertArrayHasKey('signed', $this->config);
        $this->assertTrue($this->config['signed'] ?? false);
    }

    public function testExpires(): void
    {
        // Given
        $this->configure();

        // When
        $this->expires(123);

        // Then
        $this->assertIsArray($this->config);
        $this->assertArrayHasKey('expireSeconds', $this->config);
        $this->assertEquals(123, $this->config['expireSeconds'] ?? 0);
    }

    public function testExpiresWithInvalidValue(): void
    {
        // Given
        $this->configure();

        // When
        $this->expires(-1);

        // Then
        $this->assertIsArray($this->config);
        $this->assertArrayNotHasKey('expireSeconds', $this->config);
    }

    public function testGetConfiguration(): void
    {
        // Given
        $this->configure();

        // When
        $configuration = $this->getConfiguration();

        // Then
        $this->assertIsArray($configuration);
        $this->assertEmpty($configuration);
    }

    public function testGetConfigurationWithoutInitialization(): void
    {
        // Given - When
        $configuration = $this->getConfiguration();

        // Then
        $this->assertIsArray($configuration);
        $this->assertEmpty($configuration);
    }
}
