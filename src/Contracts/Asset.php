<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Contracts;

interface Asset
{
    /**
     * Get asset URL.
     *
     * @return string
     */
    public function getUrl(): string;
}
