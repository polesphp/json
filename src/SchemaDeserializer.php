<?php

namespace Poles\Json;

use Poles\Json\Types\ObjectType;

class SchemaDeserializer extends Deserializer
{
    public function __construct(Schema $schema)
    {
        parent::__construct(new ObjectType($schema));
    }
}