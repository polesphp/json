<?php

namespace Poles\Json\Types;

class NullType implements Type
{
    public function check($value): bool
    {
        return is_null($value);
    }

    public function coerce($value)
    {
        return null;
    }
}
