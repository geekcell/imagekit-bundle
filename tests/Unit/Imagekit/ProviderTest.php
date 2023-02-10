<?php

namespace Imagekit\Tests\Unit\Imagekit;

use GeekCell\ImagekitBundle\Imagekit\Provider;
use ImageKit\ImageKit;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    public function testProvideWithPath(): void
    {
        // Given
        $imagePath = 'foo/bar/baz.jpg';
        $config = ['path' => $imagePath];
        $fakeUrl = 'https://ik.imagekit.io/fake-url';

        /** @var Mockery\MockInterface $imageKitMock */
        $imageKitMock = Mockery::mock(ImageKit::class);
        $imageKitMock->shouldReceive('url')->with($config)->andReturn($fakeUrl);

        $provider = Mockery::mock(Provider::class . '[path,sourceUrl,getConfiguration]', [$imageKitMock]);
        $provider->shouldReceive('path')->with($imagePath)->andReturnSelf();
        $provider->shouldReceive('path')->never();
        $provider->shouldReceive('getConfiguration')->andReturn($config);

        // When

        /** @var Provider $provider */
        $result = $provider->provide($imagePath);

        // Then
        $this->assertEquals($fakeUrl, $result->getUrl());
    }

    public function testProvideWithSourceUrl(): void
    {
        // Given
        $sourceUrl = 'https://example.com/foo/bar/baz.jpg';
        $config = ['src' => $sourceUrl];
        $fakeUrl = 'https://ik.imagekit.io/fake-url';

        /** @var Mockery\MockInterface $imageKitMock */
        $imageKitMock = Mockery::mock(ImageKit::class);
        $imageKitMock->shouldReceive('url')->with($config)->andReturn($fakeUrl);

        $provider = Mockery::mock(Provider::class . '[path,sourceUrl,getConfiguration]', [$imageKitMock]);
        $provider->shouldReceive('path')->never();
        $provider->shouldReceive('sourceUrl')->with($sourceUrl)->andReturnSelf();
        $provider->shouldReceive('getConfiguration')->andReturn($config);

        // When

        /** @var Provider $provider */
        $result = $provider->provide($sourceUrl);

        // Then
        $this->assertEquals($fakeUrl, $result->getUrl());
    }
}
