# Generating random strings

## Current status
### General
[![Build Status](https://secure.travis-ci.org/nokitakaze/php-randomstring.png?branch=master)](http://travis-ci.org/nokitakaze/php-randomstring)
![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nokitakaze/php-randomstring/badges/quality-score.png?b=master)
![Code Coverage](https://scrutinizer-ci.com/g/nokitakaze/php-randomstring/badges/coverage.png?b=master)
<!-- [![Latest stable version](https://img.shields.io/packagist/v/nokitakaze/randomstring.svg?style=flat-square)](https://packagist.org/packages/nokitakaze/randomstring) -->

### HHVM Version
[![Build Status](https://secure.travis-ci.org/nokitakaze/php-randomstring.png?branch=hhvm)](http://travis-ci.org/nokitakaze/php-randomstring)
![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nokitakaze/php-randomstring/badges/quality-score.png?b=hhvm)
![Code Coverage](https://scrutinizer-ci.com/g/nokitakaze/php-randomstring/badges/coverage.png?b=hhvm)

## Usage
At first
```bash
composer require nokitakaze/randomstring
```

And then
```php
require_once 'vendor/autoload.php';
use \NokitaKaze\RandomString\RandomString;

$random_string1 = new RandomString::generate(10);
$random_string2 = new RandomString::generate(10, 
    RandomString::INCLUDE_NUMERIC | RandomString::INCLUDE_UPPER_LETTERS);
```
