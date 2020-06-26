ECHO "Executing Tests"
CALL vendor\bin\codecept.bat run unit
ECHO "Copying the Parameters File"
COPY vendor\symfony\framework-standard-edition\app\config\parameters.yml.dist vendor\symfony\framework-standard-edition\app\config\parameters.yml
ECHO "Copying the default Routing File in Bundle"
DEL vendor\symfony\framework-standard-edition\app\config\routing.yml
COPY config\routing.yml vendor\symfony\framework-standard-edition\app\config\routing.yml 
ECHO "Generating the PaymentBundle"
php app/console generate:bundle --namespace=PaymentBundle --dir=src/ --bundle-name=PaymentBundle
ECHO "Creating Bundle files"
COPY bundle_files\PaymentBundle\Resources\config\*.* src\PaymentBundle\Resources\config\
COPY bundle_files\PaymentBundle\Command src\PaymentBundle\
COPY bundle_files\PaymentBundle\DateIdentifier.php src\PaymentBundle\
COPY bundle_files\PaymentBundle\PaymentDateIdentifier.php src\PaymentBundle\
COPY bundle_files\PaymentBundle\BonusPaymentDateIdentifier.php src\PaymentBundle\
CALL composer.bat dump-autoload
ECHO "Generating the ProcessMonth Command"
php app\console generate:command PaymentBundle ProcessMonth
COPY bundle_files\PaymentBundle\Command\ProcessMonthCommand.php src\PaymentBundle\Command\ProcessMonthCommand.php
ECHO "Executing the Application"
php app\console ProcessMonth %1
