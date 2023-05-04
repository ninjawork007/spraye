<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
  function getProductByJob($where_arr){

    $CI =& get_instance();


        $CI->db->select('*');
        
        $CI->db->from('job_product_assign');

        if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }
        $CI->db->join('products','products.product_id=job_product_assign.product_id','inner');
        
        $result = $CI->db->get();

        $data = $result->result();
        return $data;
  }

function getProductByReport($where_arr){

    $CI =& get_instance();


    $CI->db->select('*');

    $CI->db->from('report_product');

    if (is_array($where_arr)) {
        $CI->db->where($where_arr);
    }
    //$CI->db->join('products','products.product_id=report_product.product_id','inner');

    $result = $CI->db->get();

    $data = $result->result();
    return $data;
}
  
  function getProductByJobIds($where_arr){

    $CI =& get_instance();


        $CI->db->select('*');
        
        $CI->db->from('job_product_assign');

        if (is_array($where_arr) && !empty($where_arr)) {
            $CI->db->where_in('job_id',$where_arr);
        }
        $CI->db->join('products','products.product_id=job_product_assign.product_id','inner');
        
        $result = $CI->db->get();

        $data = $result->result();
        return $data;
  }

  function getActiveIngredient($where_arr){

    $CI =& get_instance();

        $CI->db->select('*');
        
        $CI->db->from("product_active_ingredient");
      
            if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }
        
        $result = $CI->db->get();
        $data = $result->result();
        return $data;

  }

  function getSalesTaxByProperty($property_id){
    $CI =& get_instance(); 
    $CI->db->select('*');     
    $CI->db->where('property_id',$property_id);     
    return   $CI->db->from('property_tbl')->join('sale_tax_area','sale_tax_area.sale_tax_area_id=property_tbl.sale_tax_area_id','inner')->get()->row();
  }

  function getAllSalesTaxByProperty($property_id){
    $CI =& get_instance(); 
    $CI->db->select('*');     
    $CI->db->where('property_id',$property_id);     
    return   $CI->db->from('property_sales_tax')->join('sale_tax_area','sale_tax_area.sale_tax_area_id=property_sales_tax.sale_tax_area_id','inner')->get()->result();
  }


  function getAllSalesTaxSumByInvoice($invoice_id){
    $CI =& get_instance(); 
    $CI->db->select('sum(tax_amount) as total_tax_amount');     
    $CI->db->where('invoice_id',$invoice_id);     
    return   $CI->db->from('invoice_sales_tax')->get()->row();
  }

   function getAllSalesTaxSumByInvoices($invoice_ids){
    $CI =& get_instance(); 
    $CI->db->select('sum(tax_amount) as total_tax_amount');     
    $CI->db->where_in('invoice_id',$invoice_ids);     
    $result =    $CI->db->from('invoice_sales_tax')->get()->row();
    return $result->total_tax_amount;
  }


  function getAllServicesSumByInvoice($invoice_id){
    $CI =& get_instance(); 
    $CI->db->select('sum(invoice_job_cost) as total_invoice_job_cost');     
    $CI->db->where('invoice_id',$invoice_id);     
    return   $CI->db->from('invoice_job')->get()->row();
  }


  
  function getVisIpAddr() { 
      
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) { 
        $ip = $_SERVER['HTTP_CLIENT_IP']; 
    } 
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) { 
        $ip =  $_SERVER['HTTP_X_FORWARDED_FOR']; 
    } 
    else { 
        $ip =  $_SERVER['REMOTE_ADDR']; 
    } 



    // $ip = '52.25.109.230'; 
      
    // Use JSON encoded string and converts 
    // it into a PHP variable 
    $ipdat = @json_decode(file_get_contents( 
        "http://www.geoplugin.net/json.gp?ip=" . $ip)); 
       
   
    return $ipdat;

} 




 function basysCurlProcess($api_key,$method, $url, $data = false){

      $curl = curl_init();



   switch ($method){
      case "POST":
         curl_setopt($curl, CURLOPT_POST, 1);
         if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
         break;  
      
       case "GET":
         curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
         break;
      

      default:
         if ($data)
            $url = sprintf("%s?%s", $url, http_build_query($data));
   }

   // OPTIONS:
   curl_setopt($curl, CURLOPT_URL, BASYS_URL.'api/'.$url);
   curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    "Authorization: $api_key",
    "Content-Type: application/json"
   ));
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
   $response = curl_exec($curl);

   $err = curl_error($curl);
   $http_code =  curl_getinfo($curl, CURLINFO_HTTP_CODE);
   curl_close($curl);

   $result = json_decode($response);
 
        if ($err) {

         return   array('status'=>400,'message'=>$err,'result'=>array());
          

        } else {

            switch ($http_code) {

              case 200:  # OK            
                return   array('status'=>200,'message'=>'successfully','result'=>$result);              
              
              break;
    
               case 400:  

                     return    array('status'=>400,'message'=>$result->msg);

                break;



              case 401:  

                     return    array('status'=>400,'message'=>$result->msg);

                break;
                

              case 405:  

                     return    array('status'=>400,'message'=>'Method not allowed');

                break;

                
             default:              
            return     array('status'=>400,'message'=>'Code Has Expired - Please start over.','result'=>$result,'http_code'=>$http_code);
          }


        }



      
    }


 function getOneCustomerInfo($where_arr){

    $CI =& get_instance();

        $CI->db->select('*');
        
        $CI->db->from("customers");
      
            if (is_array($where_arr)) {
            $CI->db->where($where_arr);
        }
        
        $result = $CI->db->get();
        $data = $result->row_array();
        return $data;

  }


  function Get_Address_From_Google_Maps($address='') {
    if ($address=='')   return array();


     $address = urlencode($address);
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?key='.GoogleMapKey.'&address='.$address.'&sensor=false';

    // Make the HTTP request
    $data = @file_get_contents($url);
    // Parse the json response
    $jsondata = json_decode($data,true);

    // If the json data is invalid, return empty array
    if (!check_status($jsondata))   return array();

    $address = array(
        'country' => google_getCountry($jsondata),
        'state' => google_getProvince($jsondata),
        'city' => google_getCity($jsondata),
        'street' => google_getStreet($jsondata),
        'postal_code' => google_getPostalCode($jsondata),
        'country_code' => google_getCountryCode($jsondata),
        'formatted_address' => google_getAddress($jsondata),
    );

    return $address;
    }

/* 
* Check if the json data from Google Geo is valid 
*/

function check_status($jsondata) {
    if ($jsondata["status"] == "OK") return true;
    return false;
}

/*
* Given Google Geocode json, return the value in the specified element of the array
*/

function google_getCountry($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]);
}
function google_getProvince($jsondata) {
    return Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], true);
}
function google_getCity($jsondata) {
    return Find_Long_Name_Given_Type("locality", $jsondata["results"][0]["address_components"]);
}
function google_getStreet($jsondata) {
    return Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]) . ' ' . Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]);
}
function google_getPostalCode($jsondata) {
    return Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]);
}
function google_getCountryCode($jsondata) {
    return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"], true);
}
function google_getAddress($jsondata) {
    return $jsondata["results"][0]["formatted_address"];
}

/*
* Searching in Google Geo json, return the long name given the type. 
* (If short_name is true, return short name)
*/

function Find_Long_Name_Given_Type($type, $array, $short_name = false) {
    foreach( $array as $value) {
        if (in_array($type, $value["types"])) {
            if ($short_name)    
                return $value["short_name"];
            return $value["long_name"];
        }
    }
}



 
 





