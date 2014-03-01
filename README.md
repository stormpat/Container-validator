# Cargo container validator

With the Cargo container validator you can check that a container has a valid ISO 6346 code, you can also calculate container check digits, get the cargo container owners code, group code and create new cargo container check digits.

This is a fork from a piece of code (MIT licensed) I found on the web. I have modified some parts, and made the whole thing work with [Composer](http://getcomposer.org/)

## Install

Install with composer, or clone the repo to into your project.

With composer, just add to your composer.json

```php
require: "stormpat/container-validator": "dev-master"
```

Add the composer autoloading to your bootstrap.
```php
require_once __DIR__ . '/vendor/autoload.php';
```

## Documentation

Validate container ISO codes (TEXU3070079 = valid, TEXU3070070 != valid)

```php
$validator = new Validator\Validator;
$validator->isValid('TEXU3070079'); // boolean true
$validator->isValid('TEXU3070070'); // boolean false
```

To get the diffrent segments from the code you can do,

```php
$container = $validator->validate('TEXU3070079');
print_r($container); // Array ( [0] => TEXU3070079 [1] => TEX [2] => U [3] => 307007 [4] => 9 )
```
where:

```php
array
  0 => string 'TEXU3070079' // The code being validated
  1 => string 'TEX' // The containers ownercode
  2 => string 'U' // The containers group code
  3 => string '307007' // The containers registration digit
  4 => string '9' // The containers check digit
```

How to get error messages when the container code is invalid

```php
$validator->validate('TEXU3070070');
$validator->getErrorMessages(); // The check digit does not match

$validator->validate(12345678910);
$validator->getErrorMessages(); // The container number must be a string

$validator->validate('C3P0');
$validator->getErrorMessages(); // The container number is invalid
```

Access information about the container:
```php
$validator->validate('TEXU3070070');
echo $validator->getOwnerCode(); // TEX
echo $validator->getProductGroupCode(); // U
echo $validator->getRegistrationDigit(); // 307007
echo $validator->getCheckDigit(); // 9
```

Create a check digit to a container that does not have one
```php
$validator = new Validator\Validator;
$validator->createCheckDigit('TEXU307007'); // 9
```

Generate container numbers:
```php
// $validator->generate( owner-code, product-group-code, number-start, number-end );
$validator = new Validator\Validator;
$validator->generate('TEX','U',1, 100 ));
```

## Todo

Tests are missing, and some functions are cumbersome.


## License
[MIT](http://opensource.org/licenses/MIT)

## Credits

Salute to the original author,
[gedex.adc](http://www.google.com/gedex.web.id)

