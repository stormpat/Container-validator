# Cargo container validator

With the Cargo container validator you can check that a container has a valid [ISO 6346](http://en.wikipedia.org/wiki/ISO_6346) code, you can also calculate container check digits, get the cargo container owners code, group code and create new cargo container check digits.

This is a fork from a piece of code (MIT licensed) I found on the web. I have modified some parts, and made the whole thing work with [Composer](http://getcomposer.org/)

## Install

Install with composer, or clone the repo to into your project.

With composer, just add to your composer.json

```json
"require": {
    "stormpat/container-validator": "dev-master"
}
```

Add the composer autoloading to your bootstrap.
```php
require_once __DIR__ . '/vendor/autoload.php';
```

## Documentation

Validate container ISO codes (TEXU3070079 = valid, TEXU3070070 != valid)

```php
$validator = new Validator\Validator;
$validator->isValid('TEXU3070079'); // true
$validator->isValid('TEXU3070070'); // false
```

To get the diffrent segments from the code you can do,

```php
$container = $validator->validate('TEXU3070079');
print_r($container); // Array ( [0] => TEXU3070079 [1] => TEX [2] => U [3] => 307007 [4] => 9 )
```
where:

```php
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

## Notes

- There is a JavaScript port by Sameer Shemna that can be found [here](https://github.com/sameersemna/Container-validator-JS)
- There is as well [Symfony Validator Constraint](https://github.com/ostrolucky/symfony-container-number-validator)

## License

The MIT License (MIT)

Copyright (c) 2014 Patrik Storm

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Credits

Salute to the original author,
[gedex.adc](http://www.google.com/gedex.web.id)

