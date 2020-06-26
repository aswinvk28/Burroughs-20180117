<?php

use PaymentBundle\BonusPaymentDateIdentifier;
use PaymentBundle\PaymentDateIdentifier;
use Symfony\Component\Yaml\Yaml;

class PaymentDateTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        require_once codecept_root_dir() . "/includes/phpexcel.php";
        $excelFileName = "docs" . DIRECTORY_SEPARATOR . "Payment_Dates_16-03-2015.csv";
        $CSV_READER = PHPExcel_IOFactory::createReader("csv");
        $this->phpExcelObject = $CSV_READER->load(APP_PATH . DIRECTORY_SEPARATOR . $excelFileName);
        
        $configuration = Yaml::parse(file_get_contents(APP_PATH . '/src/PaymentBundle/Resources/config/services.yml'));
        
        $this->paymentDateIdentifier = new PaymentDateIdentifier(
                $configuration['parameters']['enum_months'], 
                $configuration['parameters']['enum_days']);
        $this->bonusPaymentDateIdentifier = new BonusPaymentDateIdentifier(
                $configuration['parameters']['enum_months'], 
                $configuration['parameters']['enum_days']);
    }

    protected function _after()
    {
    }

    // test 1
    public function testPaymentDateUsage()
    {
        $rowIterator = $this->phpExcelObject->getActiveSheet()->getRowIterator(2, 13)->resetStart(2);
        
        foreach($rowIterator as $row) {
            $paymentCell = $row->getCellIterator('C')->current()->getValue();
            if($this->paymentDateIdentifier->isLastDayOfMonth($paymentCell)) {
                $this->assertFalse($this->paymentDateIdentifier->isWeekend($paymentCell), 
                        "Payment is the last day of the month but the weekend");
            } elseif($this->paymentDateIdentifier->isLastWeekDayOfMonth($paymentCell)) {
                $this->assertFalse($this->paymentDateIdentifier->isWeekend($paymentCell), 
                        "Payment is the the last week day of the month but the weekend");
            }
        }
    }
    
    // test 2
    public function testBonusPaymentDateUsage()
    {
        $rowIterator = $this->phpExcelObject->getActiveSheet()->getRowIterator(2, 13)->resetStart(2);
        
        foreach($rowIterator as $row) {
            $paymentCell = $row->getCellIterator('D')->current()->getValue();
            $month = $row->getCellIterator('B')->current()->getValue();
            $dateInfo = date_parse_from_format("d-m-Y", $paymentCell);
            $year = $dateInfo['year'];
            $midMonth = $this->bonusPaymentDateIdentifier->getMidMonth($month, $year);
            $postWednesday = new \DateTime();
            $postWednesday->setDate(
                    $year, (int) (array_search($month, $this->bonusPaymentDateIdentifier->_MONTHS) + 1), 
                    $this->bonusPaymentDateIdentifier->findPostWeekendWednesday($midMonth));
            if($this->bonusPaymentDateIdentifier->isMidMonth($paymentCell)) {
                $this->assertFalse(
                        $this->bonusPaymentDateIdentifier->isWeekend($paymentCell), 
                        "Bonus Payment Date is the 15th of the month but the weekend, {$paymentCell}");
            } elseif($postWednesday->format("d-m-Y") == $paymentCell) {
                $this->assertFalse($this->bonusPaymentDateIdentifier->isMidMonth($paymentCell), 
                        "Bonus Payment Date is a weekend and not the post wednesday of the month, {$paymentCell}");
            }
        }
    }
}