<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle;

use GeekCell\ImagekitBundle\DependencyInjection\Compiler\ProviderPass;
use GeekCell\ImagekitBundle\DependencyInjection\GeekCellImagekitExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class GeekCellImagekitBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new GeekCellImagekitExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new ProviderPass());
    }
}
