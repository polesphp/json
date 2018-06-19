<?php

namespace Poles\Json\Types;

class MixedType implements Type
{
    public function check($value): bool
    {
        return true;
    }

    public function coerce($value)
    {
        return $value;
    }

    public static function __set_state($props)
    {
        return new static();
    }
}
