<?php
     
class Webhook extends CI_Model {  


    public function callTrigger($endpoint=null,$data=0){
        
      
        $post_json = json_encode([            
            'result' => $data
        ]); //json_encode($data);
        //die(print_r($post_json));


        
        $ch = @curl_init();
        @curl_setopt($ch, CURLOPT_POST, true);
        @curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
        @curl_setopt($ch, CURLOPT_URL, $endpoint);
        @curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = @curl_exec($ch);
        $status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_errors = curl_error($ch);
        @curl_close($ch);
        
        return $response;


    }
}



//{"result":[{"customer_id":"48715","quickbook_customer_id":"0","company_id":"44","user_id":"752a2563acae4aa59f422661a66b3304","first_name":"fname","last_name":"lname","customer_company_name":"","email":"mike@schleiffarth.com","secondary_email":"","password":"","is_email":"1","phone":"3141111111","home_phone":"0","work_phone":"0","billing_street":"388 messina","customer_latitude":"","customer_longitude":"","billing_street_2":"","billing_city":"ballwin","billing_state":"MO","billing_zipcode":"63021","assign_property":"","customer_status":"1","billing_type":"0","autosend_invoices":"1","autosend_frequency":"daily","created_at":"2023-03-16 08:59:48","updated_at":"0000-00-00 00:00:00","basys_autocharge":"0","clover_autocharge":"0","basys_customer_id":null,"is_mobile_text":"0","password_reset_link":null,"reset_link_expire":null,"customer_clover_token":null,"clover_acct_id":"0","pre_service_notification":"[]","alerts":null}]}

//{"result":{"property_id":"55133","tags":"3,4"}}