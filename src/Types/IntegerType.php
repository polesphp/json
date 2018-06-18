<?php

namespace Poles\Json\Types;

class IntegerType implements Type
{
    public function check($value): bool
    {
        return is_int($value);
    }

    public function coerce($value): int
    {
        return (int)$value;
    }
}