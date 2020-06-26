#! bin/bash

composer init
composer require sensio/generator-bundle 2.8.2
composer dump-autoload

vendor/bin/codecept bootstrap
vendor/bin/codecept build
vendor/bin/codecept generate:test unit PaymentDate

vendor/bin/codecept run unit

cp .\vendor\symfony\framework-standard-edition\app\console .\app\console
# After altering vendor/autoload.php line

php app/console generate:bundle --namespace=PaymentBundle --dir=Y:\Applications\Inviqa\BurroughsTestApp\src --bundle-name=PaymentBundle

php app/console generate:command PaymentBundle ProcessMonth
