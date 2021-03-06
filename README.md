# poles-json

`poles-json` is a type-safe, class-oriented JSON (de)serialization library.

## Goals

* Clean, simple API
* Strict on errors (always throw exceptions)
* Strict type-checking by default
* Encourage clean code by defining pure PHP data structures that describe input.

## Non-goals

* Bridging for any PHP web framework
* Mapping JSON properties to getter and setter methods. I don't do the Java Bean thing.
* Value validation. This library will check that your value is a string, but will never validate that this string has a minimum of `n` characters or that it matches a certain regular expression. If you need such validation logic, this library combines nicely with [Symfony's Validation component](https://symfony.com/doc/current/validation.html).

## Usage

First, define a pure PHP data structure which represents your JSON schema:

```php
<?php

class Person
{
  /** @var int */
  public $id;
  
  /** @var string */
  public $firstname;

  /** @var string */
  public $lastname

  /** @var Address[] */
  public $addresses;
}

class Address
{
  /** int */
  public $civicNo;

  /** string */
  public $street;

  /** string */
  public $city;

  /** string */
  public $country;

  /** string */
  public $postalCode;
}
```

Note that type annotations are standard [phpDocumentor](https://phpdoc.org) tags.

Once your structure is properly defined, you can easily convert it to and from JSON like so:

```php
<?php

use Poles\Json\ClassSerializer;
use Poles\Json\SerializerConfig;

$subject = <<<JSON
{
  "id": 5,
  "firstname": "John",
  "lastname": "Doe",
  "addresses": [
    {
      "civicNo": 1080,
      "street": "Snowboarding Street",
      "city": "Montreal",
      "country": "Canada",
      "postalCode": "H2K1L3"
    }
  ]
}
JSON;

$serializer = new ClassSerializer(Person::class, new SerializerConfig());

// ----- De-serialization -------
$personInstance = $serializer->deserialize($subject);

// ----- Serialization -----
$originalJson = $serializer->serialize($personInstance);
```

## Errors

This library throws exceptions on error. The possible errors during de-serialization are:

* `Poles\Json\Exceptions\EncodeException`: An error occured during call to `json_encode` (serialization only).
* `Poles\Json\Exceptions\DecodeException`: An error occured during call to `json_decode` (de-serialization only).
* `Poles\Json\Exceptions\TypeMismatchException`: A JSON value does not match the expected type or shape of a property.
* `Poles\Json\Exceptions\UnsupportedTypeException`: A PHP property is annotated with an unsupported type (see supported types below).
* `Poles\Json\Exceptions\UnresolvableClass`: A PHP property is annotated with a class name, but the class does not exist.

## Supported type annotations

The [phpDocumentor](https://docs.phpdoc.org/guides/types.html) type annotations supported are:

* `string`
* `int` or `integer`
* `float`
* `bool` or `boolean`
* `array` (elements of the array are NOT type-checked)
* `null` (expects the value `null` and nothing else)
* Class references such as `MyClass`
* Typed arrays such as `int[]`, `MyClass[]` (in this case, each element of the array is recursively type-checked)
* Compound types such as `int|string|null`

## Configuration

Here's an example of more complex configuration for the serializer:

```php
<?php

use Poles\Json\ClassSerializer;
use Poles\Json\SerializerConfig;

$config = new SerializerConfig();
$config->setCacheDirectory(sys_get_temp_dir()); // Writeable cache directory for production environments
$config->setMaxDepth(200); // Same as the "max depth" argument of json_encode
$config->setOptions(JSON_PRETTY_PRINT); // Same as the "options" argument of json_encode

$serializer = new ClassSerializer(MyClass::class, $config);

```

# Future work

Here is an informal list of future improvements for this library:

* Looser, "coerse" mode that only throws if a type cannot be co-erced into the expected type.
* Define name re-mapping logic, e.g. (`public $dateOfBirth` becomes JSON property `date_of_birth`)
* Have a nice DSL to explicitely define a JSON schema without inferring via Reflection.

# License

All work found under this repository is licensed under the [Apache License 2.0](LICENSE).

# Contributing

If you wish to contribute to this project, make sure to read the [Contribution Guidelines](CONTRIBUTING.md) first!

