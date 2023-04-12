<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function unitConversion($value, $unit1, $unit2, $product_type){
    // die(print_r($unit1));
    $value2 = $value;
    if ($unit1 == $unit2){
        return $value2;
    }
    elseif ($unit2 == 'Quart(s)' || $unit2 == 'Quart' || $unit2 == 'Quarts'){
        if ($unit1 == 'Gallon(s)' || $unit1 == 'Gallon' || $unit1 == 'Gallons'){
            $value2 = $value * 0.25;
        } elseif ($unit1 == 'Liter(s)' || $unit1 == 'Liters' || $unit1 == 'Liter' || $unit1 == 'Litre' || $unit1 == 'Litres'){
            $value2 = $value * 0.946352946;
        } elseif ($unit1 == 'Fluid Ounce(s)' || $unit1 == 'Fluid Ounce' || ($unit1 == 'Ounce(s)' && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))  || ($unit1 == "Ounces" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10)) || ($unit1 == "Oz" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))){
            $value2 = $value * 32;
        } elseif ($unit1 == 'Pint(s)' || $unit1 == 'Pint' || $unit1 == 'Pints'){
            $value2 = $value * 2;
        } 
    }
    elseif ($unit2 == 'Gallon(s)' || $unit2 == 'Gallon' || $unit2 == 'Gallons'){
        if($unit1 == 'Fluid Ounce(s)' || $unit1 == 'Fluid Ounce' || ($unit1 == 'Ounce(s)' && ($product_type == 0 || $product_type == 3 ||  $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))  || ($unit1 == "Ounces" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10)) || ($unit1 == "Oz" && ($product_type == 0 || $product_type == 3 || $product_type == 0 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))){
            $value2 = $value * 128;        } 
        elseif ($unit1 == 'Liter(s)' || $unit1 == 'Liters' || $unit1 == 'Liter' || $unit1 == 'Litre' || $unit1 == 'Litres') {
            $value2 = $value * 3.785411784;        } 
        elseif ($unit1 == 'Pint(s)' || $unit1 == 'Pint' || $unit1 == 'Pints'){
            $value2 = $value * 8;
        }  elseif ($unit1 == 'Quart(s)' || $unit1 == 'Quart' || $unit1 == 'Quarts'){
            $value2 = $value * 4;
        }
    } elseif ($unit2 == 'Liter(s)' || $unit2 == 'Liters' || $unit2 == 'Liter' || $unit2 == 'Litre' || $unit2 == 'Litres'){
        if ($unit1 == 'Gallon(s)' || $unit1 == 'Gallon' || $unit1 == 'Gallons'){
            $value2 = $value * 0.2641720524;
        } elseif ($unit1 == 'Fluid Ounce(s)' || $unit1 == 'Fluid Ounce' || ($unit1 == 'Ounce(s)' && ($product_type == 0 || $product_type == 3 ||  $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))  || ($unit1 == "Ounces" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10)) || ($unit1 == "Oz" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))){
            $value2 = $value * 33.814022702;
        } elseif ($unit1 == 'Pint(s)' || $unit1 == 'Pint' || $unit1 == 'Pints'){
            $value2 = $value * 2.1133764189;
        } elseif ($unit1 == 'Quart(s)' || $unit1 == 'Quart' || $unit1 == 'Quarts'){
            $value2 = $value * 1.0566882094;
        }
    } elseif ($unit2 == 'Pint(s)' || $unit2 == 'Pint' || $unit2 == 'Pints'){
        if ($unit1 == 'Gallon(s)' || $unit1 == 'Gallon' || $unit1 == 'Gallons'){
            $value2 = $value * 0.125;
        } elseif ($unit1 == 'Fluid Ounce(s)' || $unit1 == 'Fluid Ounce' || ($unit1 == 'Ounce(s)' && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))  || ($unit1 == "Ounces" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10)) || ($unit1 == "Oz" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))){
            $value2 = $value * 16;
        } elseif ($unit1 == 'Liter(s)' || $unit1 == 'Liters' || $unit1 == 'Liter' || $unit1 == 'Litre' || $unit1 == 'Litres'){
            $value2 = $value * 0.473176473;
        }  elseif ($unit1 == 'Quart(s)' || $unit1 == 'Quart' || $unit1 == 'Quarts'){
            $value2 = $value * 0.5;
        }
    } elseif ($unit2 == 'Fluid Ounce(s)' || $unit2 == 'Fluid Ounce' || ($unit2 == 'Ounce(s)' && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))  || ($unit2 == "Ounces" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10)) || ($unit2 == "Oz" && ($product_type == 0 || $product_type == 3 || $product_type == 4 || $product_type == 8 || $product_type == 9 || $product_type == 10))){

        if ($unit1 == 'Gallon(s)' || $unit1 == 'Gallon' || $unit1 == 'Gallons'){
            $value2 = $value * 0.0078125;
        } elseif ($unit1 == 'Liter(s)' || $unit1 == 'Liters' || $unit1 == 'Liter' || $unit1 == 'Litre' || $unit1 == 'Litres'){
            $value2 = $value * 0.0295735296;
        } elseif ($unit1 == 'Pint(s)' || $unit1 == 'Pint' || $unit1 == 'Pints'){
            $value2 = $value * 0.0625;
        } elseif ($unit1 == 'Quart(s)' || $unit1 == 'Quart' || $unit1 == 'Quarts'){
            $value2 = $value * 0.03125;
        }
    } elseif ($unit2 == 'Pound(s)' || $unit2 == 'Pound' || $unit2 == 'Pounds' || $unit2 == 'Lb'){
        if ($unit1 == 'Ounce(s)' || $unit1 == 'Ounce' || $unit1 == 'Ounces' || $unit1 == 'Oz'){
            $value2 = $value * 16;
        } elseif ($unit1 == 'Kilogram(s)' || $unit1 == 'Kilogram' || $unit1 == 'Kilograms' || $unit1 == 'Kg'){
            $value2 = $value * 0.45359237;
        } elseif ($unit1 == 'Ton(s)' || $unit1 == 'Ton' || $unit1 == 'Tons'){
            $value2 = $value * 0.0005;
        } elseif ($unit1 == 'Gram(s)' || $unit1 == 'Gram' || $unit1 == 'Grams'){
            $value2 = $value * 453.59237;
        } 
    } elseif ($unit2 == 'Kilogram(s)' || $unit2 == 'Kilogram' || $unit2 == 'Kilograms' || $unit2 == 'Kg'){
        if ($unit1 == 'Ounce(s)' || $unit1 == 'Ounce' || $unit1 == 'Ounces' || $unit1 == 'Oz'){
            $value2 = $value * 35.27396195;
        } elseif ($unit1 == 'Pound(s)' || $unit1 == 'Pound' || $unit1 == 'Pounds' || $unit1 == 'Lb'){
            $value2 = $value * 2.2046226218;
        } elseif ($unit1 == 'Ton(s)' || $unit1 == 'Ton' || $unit1 == 'Tons'){
            $value2 = $value * 0.0011023113;
        } elseif ($unit1 == 'Gram(s)' || $unit1 == 'Gram' || $unit1 == 'Grams'){
            $value2 = $value * 1000;
        }
    } elseif ($unit2 == 'Ton(s)' || $unit2 == 'Ton' || $unit2 == 'Tons'){
        if ($unit1 == 'Ounce(s)' || $unit1 == 'Ounce' || $unit1 == 'Ounces' || $unit1 == 'Oz'){
            $value2 = $value * 32000;
        } elseif ($unit1 == 'Pound(s)' || $unit1 == 'Pound' || $unit1 == 'Pounds' || $unit1 == 'Lb'){
            $value2 = $value * 2000;
        } elseif ($unit1 == 'Kilogram(s)' || $unit1 == 'Kilogram' || $unit1 == 'Kilograms' || $unit1 == 'Kg'){
            $value2 = $value * 907.18474;
        } elseif ($unit1 == 'Gram(s)' || $unit1 == 'Gram' || $unit1 == 'Grams'){
            $value2 = $value * 907184.74;
        }
    } elseif ($unit2 == 'Ounce(s)' || $unit2 == 'Ounce' || $unit2 == 'Ounces' || $unit2 == 'Oz'){
        if ($unit1 == 'Ton(s)' || $unit1 == 'Ton' || $unit1 == 'Tons'){
            $value2 = $value * 0.00003125;
        } elseif ($unit1 == 'Pound(s)' || $unit1 == 'Pound' || $unit1 == 'Pounds' || $unit1 == 'Lb'){
            $value2 = $value * 0.0625;
        } elseif ($unit1 == 'Kilogram(s)' || $unit1 == 'Kilogram' || $unit1 == 'Kilograms' || $unit1 == 'Kg'){
            $value2 = $value * 0.0283495231;
        } elseif ($unit1 == 'Gram(s)' || $unit1 == 'Gram' || $unit1 == 'Grams'){
            $value2 = $value * 28.349523125;
        }
    } elseif ($unit2 == 'Gram(s)' || $unit2 == 'Gram' || $unit2 == 'Grams'){
        if ($unit1 == 'Ton(s)' || $unit1 == 'Ton' || $unit1 == 'Tons'){
            $value2 = $value * 0.0000011023;
        } elseif ($unit1 == 'Pound(s)' || $unit1 == 'Pound' || $unit1 == 'Pounds' || $unit1 == 'Lb'){
            $value2 = $value * 0.0022046226;
        } elseif ($unit1 == 'Kilogram(s)' || $unit1 == 'Kilogram' || $unit1 == 'Kilograms' || $unit1 == 'Kg'){
            $value2 = $value * 0.001;
        } elseif ($unit1 == 'Ounce(s)' || $unit1 == 'Ounce' || $unit1 == 'Ounces' || $unit1 == 'Oz'){
            $value2 = $value * 0.0352739619;
        }
    } else {
        $value2 = $value;
    }
    return $value2;
}