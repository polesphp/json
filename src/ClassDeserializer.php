<?php

namespace Poles\Json;

class ClassDeserializer extends SchemaDeserializer
{
    public function __construct(string $className)
    {
        parent::__construct(Schema::infer($className));
    }
}
