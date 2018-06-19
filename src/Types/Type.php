<?php

namespace Poles\Json\Types;

interface Type
{
    public function check($value): bool;

    public function coerce($value);

    public static function __set_state($props);
}
