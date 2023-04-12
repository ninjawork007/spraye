<?php
/**
 * Time zone date time helper
 * @created 2023-02-17
 * 
 * @description 
 *  
 * @usage 
 * Include using 
 *  $this->load->helper('time_zone_date_time_helper');
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


function getCompanyTimeNow($timeZoneString)
{   
    $TIME_FORMAT = "H:i:s";
    
    if( isValidTimeZone($timeZoneString))
    {
        $timeZone = new \DateTimeZone($timeZoneString);
        $date = new \Datetime('now',$timeZone);      
        return $date->format($TIME_FORMAT);
    }

    //default: return time without timezone, ie, current server time.
    return date($TIME_FORMAT);
}

function getCompanyDateNow($timeZoneString)
{
    $DATE_FORMAT = "Y-m-d";    
    
    if( isValidTimeZone($timeZoneString))
    {
        $timeZone = new \DateTimeZone($timeZoneString);
        $date = new \Datetime('now',$timeZone);
        return $date->format($DATE_FORMAT);
    }

    //default: return date without timezone, ie, current server time.
    return date($DATE_FORMAT);    
}

function getCompanyDateTimeNow($timeZoneString)
{
    $DATE_TIME_FORMAT = "Y-m-d H:i:s";   
    
    if( isValidTimeZone($timeZoneString))
    {
        $timeZone = new \DateTimeZone($timeZoneString);
        $date = new \Datetime('now',$timeZone);
        return $date->format($DATE_TIME_FORMAT);
    }

    //default: return date without timezone, ie, current server time.
    return date($DATE_TIME_FORMAT);
}

function isValidTimeZone($timeZoneString)
{
    $validTimeZones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
    
    if (in_array($timeZoneString, $validTimeZones))
    { return true; }
    
    return false;
}

