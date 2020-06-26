<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle;

/**
 * Description of DateIdentifier
 *
 * @author aswin.vijayakumar
 */
class DateIdentifier 
{
    public $_DAYS;
    public $_MONTHS;
    
    public function __construct($_MONTHS, $_DAYS)
    {
        $this->_MONTHS = $_MONTHS;
        $this->_DAYS = $_DAYS;
    }
    
    public function isLeapYear($year)
    {
        return ($year % 4 == 0);
    }
    
    public function isWeekend($date)
    {
        $dateCreated = date_create_from_format("d-m-Y", $date);
        $day = date_format($dateCreated, 'l');
        if ($day !== 'Sunday' && $day !== 'Saturday') {
            return false;
        }
        return true;
    }
    
    public function isWeekday($date)
    {
        $dateCreated = date_create_from_format("d-m-Y", $date);
        $day = date_format($dateCreated, 'l');
        if ($day === 'Sunday' || $day === 'Saturday') {
            return false;
        }
        return true;
    }
    
    public function findPostWeekendWednesday($date)
    {
        $dateCreated = date_create_from_format("d-m-Y", $date);
        $day = date_format($dateCreated, "l");
        $dateValue = (int) date_format($dateCreated, "d");
        if ($day == "Sunday") {
            $dateValue += 3;
        } elseif($day == "Saturday") {
            $dateValue += 4;
        }
        return $dateValue;
    }
}
