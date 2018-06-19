<?php

namespace Poles\Json;

use Poles\Json\Exceptions\TypeMismatchException;
use Poles\Json\Schema\NullSchemaCache;
use Poles\Json\Schema\Schema;
use Poles\Json\Schema\SchemaCache;
use Poles\Json\Schema\SchemaReflector;

class ClassSerializer extends SchemaSerializer
{
    /** @var string */
    private $className;

    public function __construct(string $className, SerializerConfig $config)
    {
        $this->className = $className;
        $schema = $this->getSchemaFromClassName($config);
        parent::__construct($schema, $config);
    }

    public function serialize($value): string
    {
        if (!is_a($value, $this->className)) {
            throw new TypeMismatchException();
        }
        return parent::serialize($value);
    }

    private function getSchemaFromClassName(SerializerConfig $config): Schema
    {
        $cacheDir = $config->getCacheDirectory();
        $cache = empty($cacheDir) ? new NullSchemaCache() : new SchemaCache($cacheDir);
        return (new SchemaReflector($this->className, $cache))->reflect();
    }
}
