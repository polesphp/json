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

    /** @var SerializerConfig */
    private $config;

    public function __construct(Type $rootType, SerializerConfig $config)
    {
        $this->rootType = $rootType;
        $this->config = $config;
    }

    public function serialize($value): string
    {
        $result = json_encode($value, $this->config->getOptions(), $this->config->getMaxDepth());
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
