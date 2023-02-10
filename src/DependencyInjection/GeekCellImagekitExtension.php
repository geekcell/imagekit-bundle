<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\DependencyInjection;

use GeekCell\ImagekitBundle\Imagekit\Provider;
use GeekCell\ImagekitBundle\Imagekit\ProviderRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class GeekCellImagekitExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('geek_cell_imagekit.public_key', $config['public_key']);
        $container->setParameter('geek_cell_imagekit.private_key', $config['private_key']);
        $container->setParameter('geek_cell_imagekit.base_url', $config['base_url']);

        $container->setDefinition(Provider::class, (new Definition(Provider::class)));
        $container->setDefinition(ProviderRegistry::class, (new Definition(ProviderRegistry::class))->setPublic(true));

        foreach ($config['configurations'] as $providerName => $providerConfig) {
            $this->registerProvider($container, $providerName, $providerConfig);
        }
    }

    /**
     * Register a named provider.
     *
     * @param ContainerBuilder $container
     * @param string $providerName
     * @param array<string, mixed> $providerConfig
     *
     * @return void
     */
    private function registerProvider(ContainerBuilder $container, string $providerName, array $providerConfig): void
    {
        $baseUrl = $container->getParameter('geek_cell_imagekit.base_url');
        $providerEndpoint = $baseUrl . $providerConfig['endpoint'];

        $providerDefiniton = (new Definition(Provider::class))
                ->setFactory([Provider::class, 'create'])
                ->setArguments(['
                    %geek_cell_imagekit.public_key%',
                    '%geek_cell_imagekit.private_key%',
                    $providerEndpoint,
                ])
                ->addTag('geek_cell_imagekit.provider')
                ->setPublic(true);

        if (true === $providerConfig['signed']) {
            $providerDefiniton->addMethodCall('signed');
        }

        foreach ($providerConfig['transformation'] as $key => $value) {
            $providerDefiniton->addMethodCall('transform', [$key, $value]);
        }

        if (false !== $providerConfig['expires']) {
            $providerDefiniton->addMethodCall('expires', [$providerConfig['expires']]);
        }

        $container->setDefinition("geek_cell_imagekit.provider.{$providerName}", $providerDefiniton);
    }
}
