<?php

namespace Poles\Json\Types;

class StringType implements Type
{
    public function check($value): bool
    {
        return is_string($value);
    }

    public function coerce($value): string
    {
        return (string)$value;
    }
}