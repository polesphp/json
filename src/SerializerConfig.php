<?php

namespace Poles\Json;

class SerializerConfig
{
    /** @var int */
    private $options;

    /** @var int */
    private $maxDepth;

    public function __construct()
    {
        $this->options = 0;
        $this->maxDepth = 512;
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
}
