<?php

namespace Poles\Json;

class SerializerConfig
{
    /** @var int */
    private $options;

    /** @var int */
    private $maxDepth;

    /** @var string|null */
    private $cacheDirectory;

    public function __construct()
    {
        $this->options = 0;
        $this->maxDepth = 512;
        $this->cacheDirectory = null;
    }

    public function getMaxDepth(): int
    {
        return $this->maxDepth;
    }

    public function setMaxDepth(int $maxDepth): void
    {
        $this->maxDepth = $maxDepth;
    }

    public function getOptions(): int
    {
        return $this->options;
    }

    public function setOptions(int $options): void
    {
        $this->options = $options;
    }

    public function getCacheDirectory(): ?string
    {
        return $this->cacheDirectory;
    }

    public function setCacheDirectory(string $cacheDirectory): void
    {
        $this->cacheDirectory = $cacheDirectory;
    }
}
