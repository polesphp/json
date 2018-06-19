<?php

namespace Poles\Json;

use Poles\Json\Schema\Schema;
use Poles\Json\Types\ObjectType;

class SchemaSerializer extends TypeSerializer
{
    public function __construct(Schema $schema, SerializerConfig $config)
    {
        parent::__construct(new ObjectType($schema), $config);
    }
}
