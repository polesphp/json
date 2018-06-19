<?php

namespace Poles\Json;

interface Serializer
{
    public function serialize($value, int $options = 0, int $depth = 512): string;
    public function deserialize(string $string);
}
