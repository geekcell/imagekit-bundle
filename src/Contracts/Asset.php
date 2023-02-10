<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Contracts;

interface Asset
{
    public function getUrl(): string;
}
