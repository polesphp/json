<?php

namespace Poles\Json\Types;

use Poles\Json\Schema;
use ReflectionClass;

class ObjectType implements Type
{
    /** @var Schema */
    private $schema;

    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function check($value): bool
    {
        if (!is_object($value)) {
            return false;
        }
        $decodedVars = get_object_vars($value);
        foreach ($this->schema->getProperties() as $name => $type) {
            if (!isset($decodedVars[$name])) {
                return false;
            }
            if (!$type->check($decodedVars[$name])) {
                return false;
            }
        }
        return true;
    }

    public function coerce($value)
    {
        $decodedVars = get_object_vars($value);
        $class = $this->schema->getClassName();
        $result = (new ReflectionClass($class))->newInstanceWithoutConstructor();
        foreach ($this->schema->getProperties() as $name => $type) {
            $result->{$name} = $type->coerce($decodedVars[$name]);
        }
        return $result;
    }
}