# Cargo container validator

With the Cargo container validator you can check that a container has a valid ISO 6346 code, you can also calculate container check digits, get the cargo container owners code, group code and create new cargo container check digits.

This is a fork from a piece of code (MIT licensed) I found on the web. I have modified some parts, and made the whole thing work with [Composer](http://getcomposer.org/)

*Credits shall go to the orginal author.*

## Install

@todo

## Documentation

```php
$validator = new Gkunno\Validator;
$validator->validate('TEXU3070079');

$validator->getErrorMessages());
```
## License
MIT

## Credits

The original author
[gedex.adc](http://www.google.com/gedex.web.id).


