<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auth
 *
 * @author satanand
 */
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/stripe-php/init.php';

class Auth extends MY_Controller {

    //put your code here
    public function __construct() {
        parent::__construct();
       
        $this->load->library('form_validation');
        $this->load->model("Administratorsuper");
        
            if ($this->session->userdata('superadmin')) {
                return redirect("superadmin");
            }
        $this->load->model("Administrator");
        $this->load->model('Super_admin_model', 'SuperModal');
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('Company_subscription_model', 'subscription');
       
    }




  
    public function index() {

        $this->form_validation->set_rules('email', 'Email', 'valid_email|required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if($this->form_validation->run() == false)
        {
             $this->load->view('login');
        } else
        {
            $post = $this->input->post();
              
            $user = $this->Administratorsuper->getOneAdmin(array("super_admin_email" => $post['email'], "super_admin_password" => md5($post['password'])));  
            if($user)
            {
               $this->session->set_userdata("superadmin",$user);

              redirect('superadmin');
               
            }else
            {               
                $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Email</strong> id or password entered is incorrect  Please try again .</div>');
                return redirect('superadmin/auth');
            }
        }
    }

    public function signup($sid='')
    {
        if(!empty($sid))
        {
            $result = $this->SuperModal->getData(array('subscription_unique_id'=>$sid,'subscription_unique_id !='=>'ag3bk4g4hq'));
            if($result)
            {
               $this->load->view('signup', array('result'=>$result));
            }
            else
            {
                $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Invalid Link</div>');
                $this->load->view('signup', array('result'=>''));
            }
              
        } 
        else
        {
            $this->session->set_flashdata('message','<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">Invalid Link</div>');
           $this->load->view('signup', array('result'=>''));
        }
    }

    public function CheckComapnyEmail($value=''){

       $data =   $this->input->post();

      $result = $this->SuperModal->getOneCompany(array('company_email' =>$data['company_email']));
      if ($result) {
        $return_array = array('status'=>400,'message'=>"Comapny email already exits","result"=>$result );        
      } else {
        $return_array = array('status'=>200,'message'=>"You can insert this email","result"=>array() );        
      }

      echo json_encode($return_array);
   
    } 

      public function CheckUserEmail($value=''){

       $data =   $this->input->post();

       $user = $this->Administrator->getOneAdmin(array('email' =>$data['email']));
    
      if ($user) {
        $return_array = array('status'=>400,'message'=>"Email already exits","result"=>$user );        
      } else {
        $return_array = array('status'=>200,'message'=>"You can insert this email","result"=>array() );        
      }
      echo json_encode($return_array);
    }


    public function successPage()
    {
        $this->load->view('success');
    }





public function testingToken($value=''){
  

\Stripe\Stripe::setApiKey(secret_key);


$testingToken = \Stripe\Token::create([
  'card' => [
    'number' => '4242424242424242',
    'exp_month' => 11,
    'exp_year' => 2020,
    'cvc' => '314',
  ],
]);

return  $testingToken->id;

}


public function testingAll($value=''){

  \Stripe\Stripe::setApiKey(secret_key);
echo "<pre>";
$token = $this->testingToken();

    try{

       $customer = \Stripe\Customer::create(array(
         "description" => "Customer for spraye company",
         "source" => $token, // obtained with Stripe.js
         "email" => 'hemantrajak1@gmail.com',
         "name" => "Hemant rajak",
       
       ));

// print_r($customer);

       $charge = \Stripe\Charge::create(array(
          "amount" => 1250,
          "currency" => "inr",
          "customer" => $customer->id,
          'description' => 'Testing ',

       ));

      $plan =  \Stripe\Plan::create([
                'amount' => 1250,
                'currency' => 'inr',
                'interval' => 'month',
                'product' => ['name' => 'prod_GGFkpaGpyFVujo'],
       ]);



       $subscription = \Stripe\Subscription::create(array(
        "customer" => $customer->id,
        "items" => array(
               array(
                  "plan" => $plan->id,
                ),
              ),
            ));

        echo "<br>";
       
$return =   array('customer_id'=>$customer->id,'charge_id'=>$charge->id,'plan_id'=>$plan->id,'subscription_id'=>$subscription->id);

        print_r($return);


print_r($customer);
echo "<br>";
print_r($charge);
echo "<br>";
print_r($plan);
echo "<br>";
print_r($subscription);
echo "<br>";


        } catch (Exception $ex) {

            $ex = $ex->getJsonBody();

            print_r($ex);
            $error = $ex['error']['message'];

            echo $error;
            // $this->session->set_flashdata("error_message", $striperror->error->message);
            return FALSE;
        }
}




    public function stripeCharges($stripeToken){

      
      // Stripe API secret key
   

      $response = array();

      $data = $this->input->post();


      // Check whether stripe token is not empty
      if(!empty($data)){




          
          // Get token, card and item info
          // $token  = $stripeToken;
        

          // Retrieve charge details
          // $chargeJson = $charge->jsonSerialize();

          // Check whether the charge is successful

          $company_geo = $this->getLatLongByAddress($data['company_address']);

          if ($company_geo) {

                $stripe_response = $this->stripeCreateCustomerSubscribe($stripeToken,$data);



                if($stripe_response['status']==200)
                {
                     $stripe_result = $stripe_response['result'];
                     if ($stripe_result['subscription']->status=='active') {


                      
                  
                      $param = array(

                          'company_name' =>$data['company_name'],
                          'company_email' =>$data['company_email'],                    
                          'company_address' =>$data['company_address'],
                          'company_address_lat' =>$company_geo['lat'],
                          'company_address_long' =>$company_geo['long'],
                          'start_location' =>$data['company_address'],
                          'start_location_lat' =>$company_geo['lat'],
                          'start_location_long' =>$company_geo['long'],                
                          'end_location' =>$data['company_address'],
                          'end_location_lat' =>$company_geo['lat'],
                          'end_location_long' =>$company_geo['long'],                  
                          'created_at' => date("Y-m-d H:i:s"),
                          'updated_at' => date("Y-m-d H:i:s"),   
                    );



                     // $company_id = $this->SuperModal->createCompany($param);
                       $company_id = $this->SuperModal->createCompany($param);


                         $sub_param = array(

                          'company_id' =>$company_id,
                          'subscription_unique_id' =>$data['subscription_unique_id'],
                          "is_total_price" => round($data["is_total_price"], 2),
                          'stripe_customer_id' =>$stripe_result['customer']->id,
                          'plan_id' =>$stripe_result['plan']->id,
                          'subscription_id' =>$stripe_result['subscription']->id,
                          'subscription_created_at' =>date("Y-m-d H:i:s"),
                         );

                         if (array_key_exists('is_additional_technition', $data)) {

                           $sub_param['is_technician_count'] = (int)$data['is_technician_count']+1;
                         }
                      
                         if (array_key_exists('is_quickbooks_price', $data)) {
                             $sub_param['is_quickbooks_price'] = $data['is_quickbooks_price'];
                        }


                      $this->subscription->createCompanySubscription($sub_param); 
                 
                          $user_id = md5(json_encode($data).date("Ymdhis"));

                          $param2 = array(
                              "company_id" => $company_id,
                              'user_id' => $user_id,
                              "user_first_name" => $data["first_name"],
                              "user_last_name" => $data["last_name"],
                              "email" => $data["company_email"],
                              "phone" => $data["phone"],
                              "password" => md5($data['password']),    
                              "role_id" => 1,
                              'created_at' => Date("Y-m-d H:i:s"),
                              'updated_at' => date("Y-m-d H:i:s"),   

                          );

                          $result = $this->Administrator->CreateOneAdmin($param2);

                          $string ='<p><br></p><p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p><br></p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE}</p><p><br></p><p>Thanks,<br></p><p><br> </p>';

                          $string2 = '<p>Hi  {CUSTOMER_NAME},</p><p><br>Below are your service details.</p><p>{SERVICE_NAME} {PROGRAM_NAME} {PROPERTY_ADDRESS} {SCHEDULE_DATE} {TECHNICIAN_MESSAGE}<br></p><p>{ADDITIONAL_INFO}<br></p><p>has been completed successfully.<br></p><p>Thanks,<br></p><p><br></p>';

                          $this->CompanyEmail->createCompanyEmail(array('company_id'=>$company_id,'job_sheduled'=>$string,'one_day_prior'=>$string,'job_completion'=>$string2));


                             $where = array('company_id' =>$company_id);

                             $email_array = array(
                                  'name' => $data['first_name'].' '.$data['last_name'],
                                  'email' => $data['company_email'],
                                  'password' => $data['password'],
                                  'role' => 'Admin',                
                             );
       

                             $email_array['setting_details'] = $this->SuperModal->getOneCompany($where);

                             $body  = $this->load->view('admin/email/user_email',$email_array,TRUE);
       
                             $subject =  $data['company_name'].' Admin';

                      
                            $company_email_details = $this->Administratorsuper->getOneSuperAdmin();
                
                              $res =   Send_Mail_dynamic($company_email_details,$data['company_email'], array("name" => $data['company_name'], "email" => $data["company_email"]),  $body, $subject);      


                        $response = array(
                            'status' => 1,
                            'company_id' => $company_id,
                            'user_id' => $result,
                            'msg' => 'Your payment was successful.',
                            
                        );
                    
                
                                                
                    } else {

                         $response = array(
                          'status' => 0,
                          'msg' => 'Payment not successful. Please try again'
                         );


                    }    

                }
                else
                {
                  $response = array(
                    'status' => 0,
                    'msg' => $stripe_response['msg']
                  );
                }
            
          } else {


            $response = array(
              'status' => 0,
              'msg' => "Please check company address"
            );
          
            
          }


      }else{
          $response = array(
              'status' => 0,
              'msg' => 'Form submission error...'
          );
      }

      // Return response
      echo json_encode($response);
  

    }




  public function tearmCondition($value=''){
    
    $file = base_url().'Spraye_Terms_and_Conditions.pdf'; 
    $filename = 'Spraye_Terms_and_Conditions.pdf'; 
      
    // Header content type 
    header('Content-type: application/pdf'); 
      
    header('Content-Disposition: inline; filename="' . $filename . '"'); 
      
    header('Content-Transfer-Encoding: binary'); 
      
    header('Accept-Ranges: bytes'); 
      
    // Read the file 
    @readfile($file); 

  }




  public function stripeCreateCustomerSubscribe($token,$data){
 
  $subscription_details = $this->SuperModal->getData(array('subscription_unique_id'=>$data['subscription_unique_id']));
  

  \Stripe\Stripe::setApiKey(secret_key);
    try{

       $customer = \Stripe\Customer::create(array(
         "description" => "Customer for ".$data["company_email"],
         "source" => $token, // obtained with Stripe.js
         "email" => $data["company_email"],
        
       ));


      $plan =  \Stripe\Plan::create([
                'amount' => (int)round($data["is_total_price"]*100, 2),
                'currency' => 'usd',
                'interval' => $subscription_details['type'],
                'product' => ['name' => $subscription_details['subscription_name'] ],
       ]);



       $subscription = \Stripe\Subscription::create(array(
        "customer" => $customer->id,
        "items" => array(
               array(
                  "plan" => $plan->id,
                ),
              ),
            ));

       
         $result =   array('customer'=>$customer,'plan'=>$plan,'subscription'=>$subscription);

         return  array('status'=>200,'result'=> $result);

        } catch (Exception $ex) {

            $ex = $ex->getJsonBody();
            $error = $ex['error']['message'];
            return  array('status'=>400,'result'=> array(), 'msg' =>$error);
        }
}


    function getLatLongByAddress($address) 
    {



      $address = urlencode($address);
      // 1017%Davis%Boulevard+Sikeston+MO+USA 
      // die();

        $geocode = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=".GoogleMapKey."&address={$address}&sensor=false");

        $output= json_decode($geocode);

        if (!empty($output->results[0]->geometry->location->lat)) 
        {
            
            $geolocation = array(
                'lat' => $output->results[0]->geometry->location->lat,
                'long' => $output->results[0]->geometry->location->lng
            );

            return $geolocation;
        } 
        else 
        {

           return false;
        } 
    }

    
}
