<?php

namespace Poles\Json;

use Poles\Json\Schema\Schema;

class ClassDeserializer extends SchemaDeserializer
{
    public function __construct(string $className)
    {
        parent::__construct(Schema::infer($className));
    }
}
