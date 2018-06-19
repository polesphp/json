<?php

namespace Poles\Json\Schema;

class NullSchemaCache extends SchemaCache
{
    public function __construct()
    {
        parent::__construct('');
    }

    public function load(string $className): ?Schema
    {
        return null;
    }

    public function write(string $className, Schema $schema): void
    {
    }
}
