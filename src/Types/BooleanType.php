<?php

namespace Poles\Json\Types;

class BooleanType implements Type
{
    public function check($value): bool
    {
        return is_bool($value);
    }

    public function coerce($value): bool
    {
        return (bool)$value;
    }

    public static function __set_state($props)
    {
        return new static();
    }
}
