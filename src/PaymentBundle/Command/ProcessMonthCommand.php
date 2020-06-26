<?php

namespace PaymentBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use PaymentBundle\PaymentDateIdentifier;
use PaymentBundle\BonusPaymentDateIdentifier;

class ProcessMonthCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('ProcessMonth')
            ->setDescription("Process the months for the next 12 months")
                ->addArgument(
                    'generation_date',
                    InputArgument::REQUIRED,
                    'Enter the Generation Date for the processing of Payment'
                );
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $generationDate = $input->getArgument('generation_date');
        $fileName = APP_PATH . '/docs/Payment_Dates_' . $generationDate . '.csv';
        
        $phpExcel = new \PHPExcel();
        $CSV_WRITER = \PHPExcel_IOFactory::createWriter($phpExcel, "csv");
        $phpExcel->createSheet();
        $phpExcel->setActiveSheetIndex();
        $worksheet = $phpExcel->getActiveSheet();
        
        $this->bonusPaymentIdentifier = new BonusPaymentDateIdentifier($this->getContainer()->getParameter("enum_months"), 
                $this->getContainer()->getParameter("enum_days"));
        $this->paymentIdentifier = new PaymentDateIdentifier($this->getContainer()->getParameter("enum_months"), 
                $this->getContainer()->getParameter("enum_days"));
        
        $this->generateHeader($worksheet);
        $this->generateRows($worksheet, $generationDate);
        $CSV_WRITER->save($fileName);
    }
    
    public function generateHeader($worksheet)
    {
        $worksheet->setCellValueByColumnAndRow(0, 1, "Sl. No.");
        $worksheet->setCellValueByColumnAndRow(1, 1, "Month");
        $worksheet->setCellValueByColumnAndRow(2, 1, "Payment Date");
        $worksheet->setCellValueByColumnAndRow(3, 1, "Bonus Payment Date");
    }
    
    public function generateRows($worksheet, $generationDate)
    {
        $dateInfo = date_parse_from_format("d-m-Y", $generationDate);
        $month = $dateInfo['month'];
        $months_pre = array_slice($this->paymentIdentifier->_MONTHS, $month);
        $months_post = array_slice($this->paymentIdentifier->_MONTHS, 0, $month);
        $year = $dateInfo['year'];
        
        $row = 1;
        foreach($months_pre as $value) {
            $worksheet->setCellValueByColumnAndRow(0, $row + 1, $row);
            $worksheet->setCellValueByColumnAndRow(1, $row + 1, $value);
            $worksheet->setCellValueByColumnAndRow(2, $row + 1, $this->generatePaymentDate($value, $year));
            $worksheet->setCellValueByColumnAndRow(3, $row + 1, $this->generateBonusPaymentDate($value, $year));
            $row++;
        }
        
        foreach($months_post as $value) {
            $worksheet->setCellValueByColumnAndRow(0, $row + 1, $row);
            $worksheet->setCellValueByColumnAndRow(1, $row + 1, $value);
            $worksheet->setCellValueByColumnAndRow(2, $row + 1, $this->generatePaymentDate($value, $year + 1));
            $worksheet->setCellValueByColumnAndRow(3, $row + 1, $this->generateBonusPaymentDate($value, $year + 1));
            $row++;
        }
    }
    
    public function generatePaymentDate($month, $year)
    {
        $lastDay = $this->paymentIdentifier->findLastDayOfMonth($month, $year);
        return $this->formatDate($lastDay, $month, $year);
    }
    
    public function generateBonusPaymentDate($month, $year)
    {
        $dateCreated = new \DateTime();
        $monthIndex = (int) (array_search($month, $this->bonusPaymentIdentifier->_MONTHS) + 1);
        $dateCreated->setDate($year, 
                $monthIndex, 
                15);
        if($this->bonusPaymentIdentifier->isWeekday($dateCreated->format("d-m-Y"))) {
            $date = 15;
        } elseif($this->bonusPaymentIdentifier->isWeekend($dateCreated->format("d-m-Y"))) {
            $date = $this->bonusPaymentIdentifier->findPostWeekendWednesday($dateCreated->format("d-m-Y"));
        }
        return $this->formatDate($date, $month, $year);
    }
    
    public function formatDate($date, $month, $year)
    {
        $dateTime = new \DateTIme();
        $dateTime->setDate($year, 
                (int) (array_search($month, $this->paymentIdentifier->_MONTHS) + 1), 
                (int) $date);
        return $dateTime->format("d-m-Y");
    }

}
