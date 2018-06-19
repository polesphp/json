<?php

namespace Poles\Json\Tests\Support;

use Poles\Json\Schema\NullSchemaCache;
use Poles\Json\Schema\Schema;
use Poles\Json\Schema\SchemaReflector;

class SchemaHelper
{
    public static function infer(string $className): Schema
    {
        return (new SchemaReflector($className, new NullSchemaCache()))->reflect();
    }
}
