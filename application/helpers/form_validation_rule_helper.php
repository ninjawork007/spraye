<?php

//if (!defined('BASEPATH')) {
//    exit('No direct script access allowed');
//}

function isValidUSzipcodeCApostcode($value)
{
    $zipPostCodeValue = stripAllWhiteSpace($value);

    if(isValidPostCodeCA($zipPostCodeValue))
    { return True; }
    else if(isValidZipCodeUS($zipPostCodeValue)) 
    { return True; }     
    else{ return False;}

    return False;
}

function isValidZipCodeUS($value)
{
    if(isNullOrEmptyString($value)){return False;}

    $nonValidatedValue = stripAllWhiteSpace($value);

    if( preg_match("/^\\d{5}(-{0,1}\\d{4})?$/", $nonValidatedValue) )
    { return True; }

    return False;
}

function isValidPostCodeCA($value)
{
    if(isNullOrEmptyString($value)){return False;}

    $nonValidatedValue = stripAllWhiteSpace($value);
    //var caZipCodeRegExp = new RegExp(/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i);

    if( preg_match("/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i", $nonValidatedValue) )
    { return True; }

    return False;
}

function stripAllWhiteSpace($value)
{ return preg_replace('/\s+/', '', $value); }

function isNullOrEmptyString($str)
{ return ($str === null || trim($str) === ''); }
