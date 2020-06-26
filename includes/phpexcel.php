<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('APP_PATH', dirname(dirname(__FILE__)));

include APP_PATH . "/vendor/autoload.php";

error_reporting(E_ALL);

//include "PHPExcel.php";

//$excelFileName = "docs" . DIRECTORY_SEPARATOR . "Payment_Dates_16-01-2018.csv";
//$CSV_READER = PHPExcel_IOFactory::createReader("csv");
//        $phpExcelObject = $CSV_READER->load(APP_PATH . DIRECTORY_SEPARATOR . $excelFileName);
//var_dump($phpExcelObject->getActiveSheet()->getRowIterator(2, 13));
//print($phpExcelObject->getActiveSheet()->getRowIterator(2, 13)->current()->getCellIterator('B')->current()->getValue());
