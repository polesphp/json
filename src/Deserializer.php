<?php

namespace Poles\Json;

interface Deserializer
{
    public function deserialize(string $json);
}
