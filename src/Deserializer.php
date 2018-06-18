<?php

namespace Poles\Json;

use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use Poles\Http\Exceptions\JsonTypeMismatchException;
use Poles\Http\Exceptions\MalformedJsonException;
use Poles\Json\Types\Type;

class Deserializer
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
            throw new MalformedJsonException(json_last_error_msg());
        }
        if (!$this->rootType->check($decoded)) {
            throw new JsonTypeMismatchException();
        }
        return $this->rootType->coerce($decoded);
    }
}