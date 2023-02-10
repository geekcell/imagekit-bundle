<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Imagekit;

use GeekCell\ImagekitBundle\Contracts\Asset as AssetInterface;

class Asset implements AssetInterface
{
    public function __construct(private readonly string $url)
    {
    }

    /**
     * Get the url of the asset.
     *
     * @codeCoverageIgnore
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
