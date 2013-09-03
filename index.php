<?php

require __DIR__.'/vendor/autoload.php';

$validator = new Gkunno\Validator;

echo '<h1>Example of valid container number : TEXU3070079</h1>';
// test valid container
// if number is valid, validate() will return array of segment code otherwise an empty array returned
$container = $validator->validate('TEXU3070079');
print_r($container);
// will return true on valid number
echo '<br>';
var_dump($validator->isValid('TEXU3070079'));
echo '<hr />';

echo '<h1>Example of invalid check digit : TEXU3070070</h1>';
// example of invalid container number, will return an empty array
$codeSegment = $validator->validate('TEXU3070070');
print_r($codeSegment);
var_dump( $validator->isValid('TEXU3070070') );
// get error messages
print_r( $validator->getErrorMessages());
echo '<hr />';

echo '<h1>Example of getting owner code, product group code, registration digit and check digit from valid container number : TEXU3070079</h1>';
// validate() or isValid() must be called before getting segmentCode
if ( $validator->isValid('TEXU3070079') ) {
    echo 'Owner code: ' . implode('', $validator->getOwnerCode()) . '<br />';
    echo 'Product group code: ' . $validator->getProductGroupCode() . '<br />';
    echo 'Registration digit: ' . implode('', $validator->getRegistrationDigit()) . '<br />';
    echo 'Check digit: ' . $validator->getCheckDigit() . '<br />';
}
echo '<hr />';

echo '<h1>Example of creating check digit from container number without check digit: TEXU307007</h1>';
echo $validator->createCheckDigit('TEXU307007');
echo '<hr />';

echo '<h1>Example of generating container number from 1 to 100</h1>';

// parameters are (left to right): owner code, product group code, number from, number to
var_dump( $validator->generate( 'TEX', 'U', 1, 100 ) );
