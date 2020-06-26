#### Burroughs Test for Processing the subsequent months from a given Generation Date

###### Introduction

The Application generates the months and the specified dates for each month when a payment is to be made considering Salary Payment and Bonus Payment

__To Test the Application__

> vendor/bin/codecept run unit

- The test case presented here is named PaymentDateTest

__To execute the Application__

> php app/console ProcessMonth 16-02-2015

The first and only argument denotes the generation date from where the set of date values are to be generated

- The command provided here is ProcessMonthCommand

###### Execute

- The test script uses a supplied file in the docs folder, whereas the application can run using any given generation date

> composer update

Windows
=======
> CD "application_root_directory"
> .\run.bat

BASH
====
> cd "application_root_directory"
> sh run.sh