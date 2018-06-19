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

```
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

```

use Poles\Json\ClassSerializer;
use Poles\Json\ClassDeserializer;

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

// ----- De-serialization -------
$deserializer = new ClassDeserializer(Person::class);
$personInstance = $deserializer->deserialize($subject);

// ----- Serialization -----
$serializer = new ClassSerializer(Person::class);
$originalJson = $serializer->serializer($personInstance);
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

# Future work

Here is an informal list of future improvements for this library:

* Class metadata caching.
* Looser, "coerse" mode that only throws if a type cannot be co-erced into the expected type.
* Define name re-mapping logic, e.g. (`public $dateOfBirth` becomes JSON property `date_of_birth`)
* Have a nice DSL to explicitely define a JSON schema without inferring via Reflection.

