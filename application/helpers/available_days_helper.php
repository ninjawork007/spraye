<?php
/**
 * Available days helper
 * @created 2023-04-01
 *
 * @description
 *
 * @usage
 * Include using
 *  $this->load->helper('available_days_helper');
 *
 */
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


/**
 * determineAvailableDays()
 *
 * @param $data
 *  Expects a data value, typically from addProperty or editProperty.
 *  The data value will look something like
 *      Array ( [confirmation] => 1 [property_title] => test [is_group_billing] =>
 *      [property_first_name] => [property_last_name] => [property_phone] =>
 *      [property_email] => [property_address] => [property_latitude] =>
 *      [property_longitude] => [property_address_2] => [property_city] => canada
 *      [property_state] => AB [property_zip] => A1A 9A9
 *      [property_type] => Residential [property_area] =>
 *      [sale_tax_area_id] => Array ( [0] => 932 ) [yard_square_feet] => 1
 *      [total_yard_grass] => [front_yard_square_feet] => 0 [back_yard_square_feet] => 0
 *      [difficulty_level] => [property_status] => 0 [tags] => Array ( [0] => 1 )
 *      [source] => [property_notes] => [assign_program] => [] [measure_map_project_id] =>
 *      [checkbox_available_monday] => on [checkbox_available_tuesday] => on
 *      [checkbox_available_wednesday] => on [checkbox_available_thursday] => on [checkbox_available_saturday] => on
 *      [checkbox_available_sunday] => on )
 *  We are only interested in the key values relating to available days:
 *      [checkbox_available_monday] => on
 *      [checkbox_available_tuesday] => on
 *      [checkbox_available_wednesday] => on
 *      [checkbox_available_thursday] => on
 *      [checkbox_available_saturday] => on
 *      [checkbox_available_sunday] => on
 *  If a day is marked as 'not available', there will be no entry for that day in $data,
 *  in that case, we mark the day as false, indicating not available.
 *  (in the above example, we do not have an entry for Friday, indicating not available)
 *
 * @return JSON array indicating if property is availble on
 *  a particular day of the week
 *  Example; {"monday": true, "tuesday": true, "Wednesday": false}
 *
 */
function determineAvailableDays($data)
{
    //$daysOfWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    $daysOfWeek = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    $availableDaysJson = array();

    foreach($daysOfWeek as $day){
        $checkboxid = 'checkbox_available_'.$day;

        if(isset($data[$checkboxid])){
            $availableDaysJson[$day] = true;
        }
        else{
            $availableDaysJson[$day] = false;
        }
    }

    return $availableDaysJson;
}

function formatAvailableDays($available_days)
{
    $daysOfWeek = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    $available_days = json_decode($available_days,true);

    $returnArray=array();

    foreach($daysOfWeek as $day) {
            if(isset($available_days[$day]) && $available_days[$day]=='true'){
                array_push($returnArray,$day);
            }
    }

    return $returnArray;
}

function availableDaysArrayForTableFilter()
{
    return ['Monday'=>'mon', 'Tuesday'=>'tue',
        'Wednesday'=>'wed', 'Thursday'=>'thu',
        'Friday'=>'fri', 'Saturday'=>'sat',
        'Sunday'=>'sun'];
}

function getDaysOfWeek()
{
    return ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
}