<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require FCPATH . 'vendor/autoload.php';
require_once APPPATH . '/third_party/stripe-php/init.php';


class Managesubscription extends MY_Controller {   

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('email')) {
            $actual_link = $_SERVER[REQUEST_URI];
            $_SESSION['iniurl'] = $actual_link;
            return redirect('admin/auth');
        }
        $this->load->library('parser');
        $this->load->helper('text');
        $this->loadModel();
        $this->load->helper(array('form', 'url'));
        $this->load->helper('job_helper');
        $this->load->library('form_validation');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *http://example.com/index.php/welcome
     * - or -
     *http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *Filename: /opt/lampp/htdocs/spraye_new_design/system/libraries/Form_validation.php


     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    private function loadModel() {

        $this->load->model('AdminTbl_property_model', 'PropertyModel');
        $this->load->model('AdminTbl_program_model', 'ProgramModel');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
        $this->load->model('AdminTbl_product_model', 'ProductModel');
        $this->load->model('Dashboard_model', 'DashboardModel');
        $this->load->model("Administrator");
        $this->load->model('Job_model', 'JobModel');
        $this->load->model('Technician_model', 'Tech');
        $this->load->model('AdminTbl_company_model', 'CompanyModel');   
        $this->load->model('AdminTbl_servive_area_model', 'ServiceArea');
        $this->load->model('Company_email_model', 'CompanyEmail');  
        $this->load->model('Administratorsuper');
        $this->load->model('Invoice_model','INV'); 
        $this->load->model('Unassign_job_delete_model','UnassignJobDeleteModal');
        $this->load->model('Sales_tax_model', 'SalesTax');          
        $this->load->model('Help_message', 'HelpMessage');          
        $this->load->model('Company_subscription_model', 'CompanySubscription');          
    }





    public function index($value=''){

        
          $page["active_sidebar"] = "dashboardnav";        
          $page["page_name"] = "Manage Subscription";

          $company_id = $this->session->userdata['company_id'];

          
          $data['subscription_details'] = $this->CompanySubscription->getOneCompanySubscription(array('company_id'=>$company_id));

          $page["page_content"] = $this->load->view("admin/manage_subbscription", $data, TRUE);

          $this->layout->superAdminTemplateTable($page);
    
    }

    public function getTechCount($value=''){
          $company_id = $this->session->userdata['company_id'];
          
       $where_check = array('company_id'=>$company_id,'role_id'=>4) ;
       $result = $this->Administrator->getNumberOFTechnician($where_check);
       echo json_encode(array('status' =>200 ,'result'=>$result->tech_count ));



    }

    public function updatePlan($value=''){

   
       $company_id = $this->session->userdata['company_id'];
       $data =  $this->input->post();
       $where = array('company_id'=>$company_id);
       $subscription_details = $this->CompanySubscription->getOneCompanySubscription($where);


       $stripe_response =  $this->updateSubscribe($data,$subscription_details);
       if($stripe_response['status']==200){
        $stripe_result = $stripe_response['result'];

          $sub_param = array(
            "is_total_price" => round($data["is_total_price"], 2),
            'plan_id' =>$stripe_result['plan']->id,
            'subscription_updated_at' =>date("Y-m-d H:i:s"),
          );

           if (array_key_exists('is_additional_technition', $data)) {

             $sub_param['is_technician_count'] = $data['is_technician_count']+1;
           } else {

             $sub_param['is_technician_count'] = 1;
           }
        
           if (array_key_exists('is_quickbooks_price', $data)) {
               $sub_param['is_quickbooks_price'] = $data['is_quickbooks_price'];
          } else {
               $sub_param['is_quickbooks_price'] =0;
          }

        
          $result =  $this->CompanySubscription->updateCompanySubscription($where,$sub_param); 

          if ($result) {
              $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Subscription</strong> updated successfully</div>');
              redirect("admin/Managesubscription");
                    
           } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Subscription</strong> not updated. Please try again.</div>');
              redirect("admin/Managesubscription");                    
              }        
        
       }  else {

          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.$stripe_response['msg'].'</div>');
          redirect("admin/Managesubscription");
       }

    }



  public function updateSubscribe($data,$subscription_details){
 
  
  \Stripe\Stripe::setApiKey(secret_key);
    try{

         $plan =  \Stripe\Plan::create([
                'amount' => (int)round($data["is_total_price"]*100, 2),
                'currency' => 'usd',
                'interval' => $subscription_details->type,
                'product' => ['name' => $subscription_details->subscription_name ],
       ]);

       $subscription = \Stripe\Subscription::retrieve($subscription_details->subscription_id);

       $resup =   \Stripe\Subscription::update($subscription_details->subscription_id, [
                'items' => [
                  [
                    'id' => $subscription->items->data[0]->id,
                    "plan" => $plan->id,
                  ],
                ],
               'prorate' => true,
          ]);


            \Stripe\Invoice::create([
              'customer' => $subscription_details->stripe_customer_id,
              'auto_advance' => true,
            ]);



       
         // $result =   array('subscription'=> $resup);
         $result =   array('plan'=> $plan,'subscription'=>$subscription);

         return  array('status'=>200,'result'=> $result);

        } catch (Exception $ex) {

            $ex = $ex->getJsonBody();
            $error = $ex['error']['message'];
            return  array('status'=>400,'result'=> array(), 'msg' =>$error);
        }
}

 
    public function getAllCharge($value=''){

      \Stripe\Stripe::setApiKey(secret_key);

       try{

            $data = $this->input->post();


           $result =   \Stripe\Charge::all([
                'limit' => (int)$data['limit'],
                'customer' => $data['stripe_customer_id'] 
            ]);



           $card_details =  \Stripe\Customer::allSources(
              $data['stripe_customer_id'],
              [ 'limit' => 1]
            );

            $data['result'] = $result;

         $html =  $this->load->view('payment_history_ajax',$data,true);
       
         echo  json_encode(array('status'=>200,'result'=> $html,'card_details'=>$card_details['data'][0]));

        } catch (Exception $ex) {

          $html = ' <tr><td colspan="5" class="last_td" >No DATA FOUND</td></tr>';

          $ex = $ex->getJsonBody();
          $error = $ex['error']['message'];
          echo  json_encode(array('status'=>400,'result'=> array(),'result'=> $html , 'msg' =>$error));
        }
   
    }

     public function addCard($token){

      $company_id = $this->session->userdata['company_id'];      
      $subscription_details = $this->CompanySubscription->getOneCompanySubscription(array('company_id'=>$company_id));

      if ($subscription_details) {

        \Stripe\Stripe::setApiKey(secret_key);

       try{

 

      $result =   \Stripe\Customer::update(
            $subscription_details->stripe_customer_id,
            [
              'source' => $token
            ]
          
          );

          $data['result'] = $result;

       
         echo  json_encode(array('status'=>200,'result'=> $result,'msg'=>'Card updated successfully'));

        } catch (Exception $ex) {
      

          $ex = $ex->getJsonBody();
          $error = $ex['error']['message'];
          echo  json_encode(array('status'=>400 , 'msg' =>$error));
        }
        
      } else {
        echo json_encode(array('status'=>400,'msg'=>'subscription not found'));
      }


    }




    public function getDetais($cust_id=''){
   
      \Stripe\Stripe::setApiKey(secret_key);



     $allSources =   \Stripe\Customer::allSources(
          $cust_id
        );

    }


  }