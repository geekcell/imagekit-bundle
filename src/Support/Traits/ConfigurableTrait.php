<?php

declare(strict_types=1);

namespace GeekCell\ImagekitBundle\Support\Traits;

trait ConfigurableTrait
{
    /**
     * @var null|array<string, mixed>
     */
    protected ?array $config = null;

    /**
     * Initialize the configuration
     *
     * @return static
     */
    public function configure(): static
    {
        $this->config = [];
        return $this;
    }

    /**
     * Add a path to the configuration
     *
     * @param string $path
     * @return static
     */
    public function path(string $path): static
    {
        $this->config['path'] = $path;
        return $this;
    }

    /**
     * Add a source url to the configuration
     *
     * @param string $sourceUrl
     * @return static
     */
    public function sourceUrl(string $sourceUrl): static
    {
        $this->config['src'] = $sourceUrl;
        return $this;
    }

    /**
     * Add a transformation (by key and value) to the configuration
     *
     * @param string $key
     * @param string $value
     *
     * @return static
     */
    public function transform(string $key, string $value): static
    {
        if (!isset($this->config['transformation'])) {
            $this->config['transformation'] = [];
        }

        $this->config['transformation'][] = [$key => $value];

        return $this;
    }

    /**
     * Add a signed flag to the configuration
     *
     * @return static
     */
    public function signed(): static
    {
        $this->config['signed'] = true;
        return $this;
    }

    /**
     * Add an expiration time to the configuration
     *
     * @param int $expires
     * @return static
     */
    public function expires(int $expires): static
    {
        if ($expires < 0) {
            return $this;
        }

        $this->config['expireSeconds'] = $expires;
        return $this;
    }

    /**
     * Return or initialize the configuration
     *
     * @return array<string, mixed>
     */
    public function getConfiguration(): array
    {
        if (!isset($this->config)) {
            $this->configure();
        }

        return $this->config;
    }
}
