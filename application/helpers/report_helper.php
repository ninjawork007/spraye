<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function estimateOfPesticideUsed($product_details, $yard_square_feet)
{

    if (!empty($product_details->application_rate) && $product_details->application_rate != 0) {

        $re = 0;
        if ($product_details->application_per == '1 Acre') {
            $re = $product_details->application_rate / 43560;
        } else {
            $re = $product_details->application_rate / 1000;
        }

        $used_mixture =  number_format($re * $yard_square_feet, 2);
        $used_mixture =  floatval($used_mixture);


        return  $used_mixture . ' ' . $product_details->application_unit;
    } else {
        return "";
    }
}

// function unitConversion($value, $unit1, $unit2){
//     $value2 = $value;
//     if ($unit1 == $unit2){
//         return $value2;
//     }
//     elseif ($unit2 == 'Quarts'){
//         if ($unit1 == 'Gallon'){
//             $value2 = $value * 0.25;
//         } elseif ($unit1 == 'Liter'){
//             $value2 = $value * 0.946352946;
//         } elseif ($unit1 == 'Fluid Ounce'){
//             $value2 = $value * 32;
//         } elseif ($unit1 == 'Pint'){
//             $value2 = $value * 2;
//         } 
//     }
//     elseif ($unit2 == 'Gallon'){
//         if($unit1 == 'Fluid Ounce'){
//             $value2 = $value * 128;        } 
//         elseif ($unit1 == 'Liter') {
//             $value2 = $value * 3.785411784;        } 
//         elseif ($unit1 == 'Pint'){
//             $value2 = $value * 8;
//         }  elseif ($unit1 == 'Quart'){
//             $value2 = $value * 4;
//         }
//     } elseif ($unit2 == 'Liter'){
//         if ($unit1 == 'Gallon'){
//             $value2 = $value * 0.2641720524;
//         } elseif ($unit1 == 'Fluid Ounce'){
//             $value2 = $value * 33.814022702;
//         } elseif ($unit1 == 'Pint'){
//             $value2 = $value * 2.1133764189;
//         } elseif ($unit1 == 'Quart'){
//             $value2 = $value * 1.0566882094;
//         }
//     } elseif ($unit2 == 'Pint'){
//         if ($unit1 == 'Gallon'){
//             $value2 = $value * 0.125;
//         } elseif ($unit1 == 'Fluid Ounce'){
//             $value2 = $value * 16;
//         } elseif ($unit1 == 'Liter'){
//             $value2 = $value * 0.473176473;
//         }  elseif ($unit1 == 'Quart'){
//             $value2 = $value * 0.5;
//         }
//     } elseif ($unit2 == 'Fluid Ounce'){
//         if ($unit1 == 'Gallon'){
//             $value2 = $value * 0.0078125;
//         } elseif ($unit1 == 'Liter'){
//             $value2 = $value * 0.0295735296;
//         } elseif ($unit1 == 'Pint'){
//             $value2 = $value * 0.0625;
//         } elseif ($unit1 == 'Quart'){
//             $value2 = $value * 0.03125;
//         }
//     } elseif ($unit2 == 'Pound'){
//         if ($unit1 == 'Ounce'){
//             $value2 = $value * 16;
//         } elseif ($unit1 == 'Kilogram'){
//             $value2 = $value * 0.45359237;
//         } elseif ($unit1 == 'Ton'){
//             $value2 = $value * 0.0005;
//         } elseif ($unit1 == 'Gram'){
//             $value2 = $value * 453.59237;
//         } 
//     } elseif ($unit2 == 'Kilogram'){
//         if ($unit1 == 'Ounce'){
//             $value2 = $value * 35.27396195;
//         } elseif ($unit1 == 'Pound'){
//             $value2 = $value * 2.2046226218;
//         } elseif ($unit1 == 'Ton'){
//             $value2 = $value * 0.0011023113;
//         } elseif ($unit1 == 'Gram'){
//             $value2 = $value * 1000;
//         }
//     } elseif ($unit2 == 'Ton'){
//         if ($unit1 == 'Ounce'){
//             $value2 = $value * 32000;
//         } elseif ($unit1 == 'Pound'){
//             $value2 = $value * 2000;
//         } elseif ($unit1 == 'Kilogram'){
//             $value2 = $value * 907.18474;
//         } elseif ($unit1 == 'Gram'){
//             $value2 = $value * 907184.74;
//         }
//     } elseif ($unit2 == 'Ounce'){
//         if ($unit1 == 'Ton'){
//             $value2 = $value * 0.00003125;
//         } elseif ($unit1 == 'Pound'){
//             $value2 = $value * 0.0625;
//         } elseif ($unit1 == 'Kilogram'){
//             $value2 = $value * 0.0283495231;
//         } elseif ($unit1 == 'Gram'){
//             $value2 = $value * 28.349523125;
//         }
//     } elseif ($unit2 == 'Gram'){
//         if ($unit1 == 'Ton'){
//             $value2 = $value * 0.0000011023;
//         } elseif ($unit1 == 'Pound'){
//             $value2 = $value * 0.0022046226;
//         } elseif ($unit1 == 'Kilogram'){
//             $value2 = $value * 0.001;
//         } elseif ($unit1 == 'Ounce'){
//             $value2 = $value * 0.0352739619;
//         }
//     } else {
//         $value2 = $value;
//     }
//     return $value2;
// }

function reduceToOneAcre($rate, $rate_per)
{
    $result = $rate;

    if ($rate_per > 1) {
        $result = $rate / $rate_per;
    }

    return $result;
}

function amountOfChemicalUsed($product_details, $tech_apply, $yard_square_feet)
{

    if (!empty($product_details->application_rate) && $product_details->application_rate != 0 && !empty($product_details->mixture_application_rate) && $product_details->mixture_application_rate != 0) {
        $re = 0;
        $re2 = 0;
        $reducedRate = reduceToOneAcre($product_details->application_rate, $product_details->application_rate_per);
        $reducedMixRate = reduceToOneAcre($product_details->mixture_application_rate, $product_details->mixture_application_rate_per);
        if ($product_details->mixture_application_per == "1 Acre") {
            // Converts to application rate per square foot
            $re = $reducedMixRate / 43560;
        } else {
            $re = $product_details->mixture_application_rate / 1000;
        }

        $estimated_mixture_applied =  $re * $yard_square_feet;

        if ($product_details->application_per == "1 Acre") {
            // Converts to application rate per square foot
            $re2 = $reducedRate / 43560;
        } else {
            $re2 = $product_details->application_rate / 1000;
        }

        $estimate_of_chemical_used =  $re2 * $yard_square_feet;

        $result =   ($tech_apply[$product_details->product_id] / $estimated_mixture_applied) *  $estimate_of_chemical_used;


        $used_mixture =  number_format($result, 2);
        $used_mixture =  floatval($used_mixture);



        return $used_mixture . ' ' . $product_details->application_unit;
    } else {
        return "";
    }
}





function reportProductDetails($report_id)
{

    $CI = &get_instance();
    $CI->db->select('*');
    $CI->db->where('report_id', $report_id);
    return   $CI->db->from('report_product')->get()->result();
}
