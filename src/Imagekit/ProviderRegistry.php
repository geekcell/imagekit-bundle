<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Imagekit;

class ProviderRegistry
{
    /**
     * @var array<string, Provider>
     */
    private array $providers = [];

    /**
     * Register a provider.
     *
     * @param string $providerName
     * @param Provider $provider
     */
    public function register(string $providerName, Provider $provider): void
    {
        $this->providers[$providerName] = $provider;
    }

    /**
     * Get a provider.
     *
     * @param string $providerName
     * @return Provider
     *
     * @throws \InvalidArgumentException
     */
    public function getProvider(string $providerName): Provider
    {
        if (!isset($this->providers[$providerName])) {
            throw new \InvalidArgumentException(
                sprintf('No provider registered for identifier %s', $providerName),
            );
        }

        return $this->providers[$providerName];
    }
}
