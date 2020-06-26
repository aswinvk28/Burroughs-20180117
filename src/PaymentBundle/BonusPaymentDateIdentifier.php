<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PaymentBundle;

use PaymentBundle\DateIdentifier;

/**
 * Description of BonusPaymentDateIdentifier
 *
 * @author aswin.vijayakumar
 */
class BonusPaymentDateIdentifier extends DateIdentifier 
{
    public function isMidMonth($date)
    {
        $dateInfo = date_parse_from_format("d-m-Y", $date);
        $day = (int) $dateInfo['day'];
        if ($day == 15) {
            return true;
        }
        return false;
    }
    
    public function getMidMonth($month, $year)
    {
        $dateCreated = new \DateTime();
        $monthIndex = (int) (array_search($month, $this->_MONTHS) + 1);
        $dateCreated->setDate($year, $monthIndex, 15);
        return $dateCreated->format("d-m-Y");
    }
}
