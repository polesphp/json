<?php

namespace Poles\Json;

use Poles\Json\Exceptions\TypeMismatchException;
use Poles\Json\Schema\Schema;

class ClassSerializer extends SchemaSerializer
{
    /** @var string */
    private $className;

    public function __construct(string $className, SerializerConfig $config)
    {
        $this->className = $className;
        parent::__construct(Schema::infer($className), $config);
    }

    public function serialize($value): string
    {
        if (!is_a($value, $this->className)) {
            throw new TypeMismatchException();
        }
        return parent::serialize($value);
    }
}
