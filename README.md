# Php sphinx-configuration-tokenizer

[![Latest Stable Version](https://poser.pugx.org/ltd-beget/sphinx-configuration-tokenizer/version)](https://packagist.org/packages/ltd-beget/sphinx-configuration-tokenizer) 
[![Total Downloads](https://poser.pugx.org/ltd-beget/sphinx-configuration-tokenizer/downloads)](https://packagist.org/packages/ltd-beget/sphinx-configuration-tokenizer)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/LTD-Beget/sphinx-configuration-tokenizer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/LTD-Beget/sphinx-configuration-tokenizer/?branch=master)
[![Documentation](https://img.shields.io/badge/code-documented-brightgreen.svg)](http://ltd-beget.github.io/sphinx-configuration-tokenizer/documentation/html/index.html)
[![License MIT](http://img.shields.io/badge/license-MIT-blue.svg?style=flat)](https://github.com/LTD-Beget/sphinx-configuration-tokenizer/blob/master/LICENSE)

Tokenize sphinx configuration and that's all, folks.

## Installation

```shell
composer require ltd-beget/sphinx-configuration-tokenizer
```

## Usage
```php
<?php
    use LTDBeget\sphinx\Tokenizer;
    
    require(__DIR__ . '/vendor/autoload.php');
    
    $config_path = realpath(__DIR__."/sphinx/sphinx.conf"); // path to your sphinx conf
    $plain_config = file_get_contents($config_path); // or some string with sphinx conf
    
    $tokenized = Tokenizer::tokenize($plain_config); // that's all, folks. All is done =)

```
## Developers
### Regenerate documentation
```shell
$ ./vendor/bin/phpdox
```

### Run tests

```shell
wget https://phar.phpunit.de/phpunit.phar
```

```shell
php phpunit.phar --coverage-html coverage
```

```shell
php phpunit.phar --coverage-clover coverage.xml
```

## License
released under the MIT License.
See the [bundled LICENSE file](LICENSE) for details.
