<?php

namespace Poles\Json;

interface Serializer
{
    public function serialize($value): string;
    public function deserialize(string $string);
}
