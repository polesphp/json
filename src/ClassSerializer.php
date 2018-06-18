<?php

namespace Poles\Json;

use function is_a;
use function json_encode;
use function json_last_error;
use function json_last_error_msg;
use Poles\Json\Exceptions\EncodeException;
use Poles\Json\Exceptions\TypeMismatchException;

class ClassSerializer implements Serializer
{
    /** @var string */
    private $className;

    /** @var int */
    private $options;

    /** @var int */
    private $depth;

    public function __construct(string $className, int $options = 0, int $depth = 512)
    {
        $this->className = $className;
        $this->options = $options;
        $this->depth = $depth;
    }

    public function serialize($object): string
    {
        if (!is_a($object, $this->className)) {
            throw new TypeMismatchException();
        }
        $result = json_encode($object, $this->options, $this->depth);
        $err = json_last_error();
        if (JSON_ERROR_NONE !== $err) {
            throw new EncodeException(json_last_error_msg(), $err);
        }
        return $result;
    }
}
