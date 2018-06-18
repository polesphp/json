<?php

namespace Poles\Json\Types;

use function is_float;

class FloatType implements Type
{
    public function check($value): bool
    {
        return is_float($value);
    }

    public function coerce($value): float
    {
        return (float)$value;
    }
}
