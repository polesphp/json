<?php

namespace Poles\Json;

use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use Poles\Json\Exceptions\DecodeException;
use Poles\Json\Exceptions\EncodeException;
use Poles\Json\Exceptions\TypeMismatchException;
use Poles\Json\Types\Type;

class TypeSerializer implements Serializer
{
    /** @var Type */
    private $rootType;

    public function __construct(Type $rootType)
    {
        $this->rootType = $rootType;
    }

    public function serialize($value, int $options = 0, int $depth = 512): string
    {
        $result = json_encode($value, $options, $depth);
        $err = json_last_error();
        if (JSON_ERROR_NONE !== $err) {
            throw new EncodeException(json_last_error_msg(), $err);
        }
        return $result;
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
