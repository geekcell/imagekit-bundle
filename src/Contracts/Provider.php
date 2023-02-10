<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Contracts;

interface Provider
{
    /**
     * Provide an asset from a path or a source url
     *
     * @param string $pathOrUrl  A path or source url
     * @return Asset
     */
    public function provide(string $pathOrUrl): Asset;
}
