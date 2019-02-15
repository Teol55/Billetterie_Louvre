<?php
/**
 * Created by PhpStorm.
 * User: tof
 * Date: 15/02/2019
 * Time: 10:48
 */

namespace App\Service;


class DayOFF
{


    //Traitement de dates
public function findDay($date)
{
    $dateTimestamp = $date->getTimestamp();
    $dateString = date_format($date, "d-m");


    $year = intval(date_format($date, "Y"));

//jours ferié Variable
    $easterDate = easter_date($year);

    $easterDay = date('j', $easterDate);
    $easterMonth = date('n', $easterDate);
    $easterYear = date('Y', $easterDate);

//Tableau des jours ferié

    $dateOff = array(
        // Dates fixes
        mktime(0, 0, 0, 1, 1, $year),  // 1er janvier
        mktime(0, 0, 0, 5, 8, $year),  // Victoire des alliés
        mktime(0, 0, 0, 7, 14, $year),  // Fête nationale
        mktime(0, 0, 0, 8, 15, $year),  // Assomption
        mktime(0, 0, 0, 11, 11, $year),  // Armistice

        // Dates variables
        mktime(0, 0, 0, $easterMonth, $easterDay + 2, $easterYear),
        mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear),
        mktime(0, 0, 0, $easterMonth, $easterDay + 51, $easterYear),
    );


}

}