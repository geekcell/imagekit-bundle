<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Tests\Integration;

use GeekCell\ImagekitBundle\Imagekit\Provider;
use GeekCell\ImagekitBundle\Imagekit\ProviderRegistry;
use GeekCell\ImagekitBundle\Tests\TestKernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

class ProviderTest extends TestCase
{
    public function testProvider(): void
    {
        // Given
        $kernel = new TestKernel('default');
        $kernel->boot();
        $container = $kernel->getContainer();

        $fooConfig = [
            'transformation' => [
                [ 'named' => 'named_foo' ]
            ]
        ];

        $barConfig = [
            'transformation' => [
                [ 'width' => '100' ],
                [ 'height' => '100' ]
            ],
            'signed' => true,
            'expireSeconds' => 3600,
        ];

        $bazConfig = [
            'transformation' => [
                [ 'quality' => '40' ],
                [ 'rotation' => '90' ],
                [ 'progressive' => true ],
                [ 'effect_contrast' => '1' ],
                [ 'format' => 'png' ],
            ],
            'signed' => true,
            'expireSeconds' => 1800,
        ];

        // When

        /** @var Provider $fooProvider */
        $fooProvider = $container->get('geek_cell_imagekit.provider.foo');

        /** @var Provider $barProvider */
        $barProvider = $container->get('geek_cell_imagekit.provider.bar');

        /** @var Provider $bazProvider */
        $bazProvider = $container->get('geek_cell_imagekit.provider.baz');

        /** @var ProviderRegistry $registry */
        $registry = $container->get(ProviderRegistry::class);

        // Then
        $this->assertEquals('public_key', $container->getParameter('geek_cell_imagekit.public_key'));
        $this->assertEquals('private_key', $container->getParameter('geek_cell_imagekit.private_key'));
        $this->assertEquals('http://example.org', $container->getParameter('geek_cell_imagekit.base_url'));

        $this->assertNotNull($fooProvider);
        $this->assertEquals($fooConfig, $fooProvider->getConfiguration());
        $this->assertSame($fooProvider, $registry->getProvider('foo'));

        $this->assertNotNull($barProvider);
        $this->assertEquals($barConfig, $barProvider->getConfiguration());
        $this->assertSame($barProvider, $registry->getProvider('bar'));

        $this->assertNotNull($bazProvider);
        $this->assertEquals($bazConfig, $bazProvider->getConfiguration());
        $this->assertSame($bazProvider, $registry->getProvider('baz'));
    }

    public function testProviderSignedWithMissingExpires(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        (new TestKernel('signed_with_missing_expires'))->boot();
    }

    public function testProviderWithMissingRequired(): void
    {
        $this->expectException(InvalidConfigurationException::class);
        (new TestKernel('missing_required'))->boot();
    }
}
