# Development

## Install Composer
`curl -sS https://getcomposer.org/installer | php`

## Install project dependencies
`php composer.phar install`

> Note: Call `php composer.phar dumpautoload` when new class is created.

## Run tests
`$ ./vendor/bin/phpunit --verbose tests`.

or specific test method can be run using `--filter`option e.g. `./vendor/bin/phpunit --verbose tests --filter testFromHttpRequestCreatesCorrectRequest`.

## Run compatibility checks
`$ ./vendor/bin/phpcs --standard=PHPCompatibility --extensions=php --runtime-set testVersion 7.4- ./src`

## Run examples
`$ cd examples`

`$ php index.php`

# Usage

See https://getcomposer.org/doc/05-repositories.md#using-private-repositories how to reference this library in your project.