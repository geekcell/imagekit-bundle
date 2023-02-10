<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Tests\Unit\DependencyInjection\Compiler;

use GeekCell\ImagekitBundle\Contracts\Provider;
use GeekCell\ImagekitBundle\DependencyInjection\Compiler\ProviderPass;
use GeekCell\ImagekitBundle\Imagekit\ProviderRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

class ProviderPassTest extends TestCase
{
    public function testProcess(): void
    {
        // Given
        $container = new ContainerBuilder();

        $providerRegistryDefinition = new Definition(ProviderRegistry::class);
        $container->setDefinition(ProviderRegistry::class, $providerRegistryDefinition);

        $firstProvider = new Definition(Provider::class);
        $firstProvider->addTag('geek_cell_imagekit.provider');
        $container->setDefinition('ignored.ignored.first', $firstProvider);

        $secondProvider = new Definition(Provider::class);
        $secondProvider->addTag('geek_cell_imagekit.provider');
        $container->setDefinition('ignored.ignored.second', $secondProvider);

        $invalidProvider = new Definition(\stdClass::class);
        $invalidProvider->addTag('geek_cell_imagekit.provider');
        $container->setDefinition('does.not.matter', $invalidProvider);

        // When
        (new ProviderPass())->process($container);

        // Then
        $calls = $providerRegistryDefinition->getMethodCalls();
        $this->assertCount(2, $calls);

        $this->assertEquals('register', $calls[0][0]);
        $this->assertEquals('first', $calls[0][1][0]);

        $this->assertEquals('register', $calls[1][0]);
        $this->assertEquals('second', $calls[1][1][0]);
    }
}
