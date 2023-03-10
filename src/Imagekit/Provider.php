<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Imagekit;

use GeekCell\ImagekitBundle\Contracts\Asset;
use GeekCell\ImagekitBundle\Contracts\Provider as ProviderInterface;
use GeekCell\ImagekitBundle\Imagekit\Asset as ImagekitAsset;
use GeekCell\ImagekitBundle\Support\Traits\ConfigurableTrait;
use ImageKit\ImageKit as ImagekitSDK;

class Provider implements ProviderInterface
{
    use ConfigurableTrait;

    /**
     * Provider constructor.
     *
     * @param ImagekitSDK $imageKit
     */
    public function __construct(private readonly ImagekitSDK $imageKit)
    {
    }

    /**
     * Factory method to create a new instance of the provider
     *
     * @param string $publicKey    ImageKit public key
     * @param string $privateKey   ImageKit private key
     * @param string $urlEndpoint  ImageKit url endpoint
     *
     * @return Provider
     */
    public static function create(string $publicKey, string $privateKey, string $urlEndpoint): self
    {
        $imageKit = new ImagekitSDK($publicKey, $privateKey, $urlEndpoint);
        return (new self($imageKit))->configure();
    }

    /**
     * {@inheritDoc}
     */
    public function provide(string $pathOrUrl): Asset
    {
        // Check whether $pathOrUrl is a path or a url
        if (filter_var($pathOrUrl, \FILTER_VALIDATE_URL)) {
            return $this->assetFromSourceUrl($pathOrUrl);
        }

        return $this->assetFromPath($pathOrUrl);
    }

    /**
     * Get an asset from a path
     *
     * @param string $path  A path
     * @return Asset
     */
    private function assetFromPath(string $path): Asset
    {
        $config = $this->path($path)->getConfiguration();
        return new ImagekitAsset($this->imageKit->url($config));
    }

    /**
     * Get an asset from a source url
     *
     * @param string $sourceUrl  A source url
     * @return Asset
     */
    private function assetFromSourceUrl(string $sourceUrl): Asset
    {
        $config = $this->sourceUrl($sourceUrl)->getConfiguration();
        return new ImagekitAsset($this->imageKit->url($config));
    }
}
