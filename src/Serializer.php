<?php

namespace Poles\Json;

interface Serializer
{
    public function serialize($object): string;
}
