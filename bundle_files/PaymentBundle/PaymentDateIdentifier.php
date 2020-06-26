<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle;

use PaymentBundle\DateIdentifier;

/**
 * Description of PaymentDateIdentifier
 *
 * @author aswin.vijayakumar
 */
class PaymentDateIdentifier extends DateIdentifier
{
    public function isLastDayOfMonth($date)
    {
        $dateInfo = date_parse_from_format("d-m-Y", $date);
        $monthIndex = $dateInfo['month'];
        if($dateInfo['day'] == $this->_DAYS[$monthIndex - 1]) {
            return true;
        }
        return false;
    }
    
    public function isLastWeekDayOfMonth($date)
    {
        if($this->isWeekday($date) && $this->isLastDayOfMonth($date)) {
            return true;
        }
        return false;
    }
    
    public function findLastDayOfMonth($month, $year, $lastDay=NULL, $monthIndex=NULL)
    {
        // increment the february database data by 1 if it is a leap year
        if($month == "February" and $this->isLeapYear($year)) {
            $this->_DAYS[1] = 29;
        }
        if (!isset($monthIndex)) {
            $monthIndex = (int) (array_search($month, $this->_MONTHS) + 1);
        }
        if (!isset($lastDay)) {
            $lastDay = (int) $this->_DAYS[($monthIndex - 1)];
        }
        $dateObject = new \DateTime();
        $dateObject->setDate($year, $monthIndex, $lastDay);
        $dateString = $dateObject->format("d-m-Y");
        if($this->isWeekend($dateString)) {
            return $this->findLastDayOfMonth($month, $year, --$lastDay, $monthIndex);
        } elseif($this->isWeekday($dateString)) {
            return $lastDay;
        }
        return $lastDay;
    }
}
