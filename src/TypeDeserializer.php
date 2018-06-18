<?php

namespace Poles\Json;

use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use Poles\Json\Exceptions\DecodeException;
use Poles\Json\Exceptions\TypeMismatchException;
use Poles\Json\Types\Type;

class TypeDeserializer implements Deserializer
{
    /** @var Type */
    private $rootType;

    public function __construct(Type $rootType)
    {
        $this->rootType = $rootType;
    }

    public function deserialize(string $json)
    {
        $decoded = json_decode($json);
        $err = json_last_error();
        if (JSON_ERROR_NONE !== $err) {
            throw new DecodeException(json_last_error_msg(), $err);
        }
        if (!$this->rootType->check($decoded)) {
            throw new TypeMismatchException();
        }
        return $this->rootType->coerce($decoded);
    }
}