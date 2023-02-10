<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\DependencyInjection\Compiler;

use GeekCell\ImagekitBundle\Imagekit\ProviderRegistry;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

use function Symfony\Component\String\u;

class ProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $providerRegistryDefinition = $container->getDefinition(ProviderRegistry::class);
        $providers = $container->findTaggedServiceIds('geek_cell_imagekit.provider');
        foreach ($providers as $providerId => $attributes) {
            $providerName = u($providerId)->afterLast('.')->toString();
            $providerRegistryDefinition->addMethodCall('register', [$providerName, new Reference($providerId)]);
        }
    }
}
