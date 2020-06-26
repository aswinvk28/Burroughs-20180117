#! bin/bash
echo "Executing Tests"
vendor/bin/codecept run unit
echo "Copying the Parameters File"
cp vendor/symfony/framework-standard-edition/app/config/parameters.yml.dist vendor/symfony/framework-standard-edition/app/config/parameters.yml
echo "Copying the default Routing File in Bundle"
rm -rf vendor/symfony/framework-standard-edition/app/config/routing.yml
cp config/routing.yml vendor/symfony/framework-standard-edition/app/config/routing.yml 
echo "Generating the PaymentBundle"
php app/console generate:bundle --namespace=PaymentBundle --dir=src/ --bundle-name=PaymentBundle
echo "Creating Bundle files"
cp bundle_files/PaymentBundle/Resources/config/*.* src/PaymentBundle/Resources/config/
cp bundle_files/PaymentBundle/DateIdentifier.php src/PaymentBundle/
cp bundle_files/PaymentBundle/PaymentDateIdentifier.php src/PaymentBundle/
cp bundle_files/PaymentBundle/BonusPaymentDateIdentifier.php src/PaymentBundle/
composer dump-autoload
echo "Generating the ProcessMonth Command"
php app/console generate:command PaymentBundle ProcessMonth
cp bundle_files/PaymentBundle/Command/ProcessMonthCommand.php src/PaymentBundle/Command/ProcessMonthCommand.php
echo "Executing the Application"
php app/console ProcessMonth $1
