<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/stripe-php/init.php';

class Managesubscription extends MY_Controller {  

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('superadmin')) {
            return redirect('superadmin/auth');
        }
        $this->load->library('parser');
        $this->load->helper('text');
        $this->loadModel();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    private function loadModel() {
        $this->load->model("Administrator");
        $this->load->model('Super_admin_model', 'SuperModal');
        $this->load->model('Company_email_model', 'CompanyEmail');
        $this->load->model('Administratorsuper');
       $this->load->model('Company_subscription_model', 'CompanySubscription');  
       $this->load->helper('super_admin_helper');  

      
    } 

    public function index($company_id) {
      $page["active_sidebar"] = "manage_subbscription";
      $page["page_name"] = "Manage Subscription";

      $data['subscription_details'] = $this->CompanySubscription->getOneCompanySubscription(array('company_id'=>$company_id));
      $where = array('company_id' => $company_id);
      $data['company_details'] = $this->SuperModal->getOneCompany($where);
      $where['role_id'] = 1;    
      $data['user_details'] = $this->Administrator->getOneAdmin($where);  

      // echo "<pre>";
      // print_r($data);
      // die();

      $page["page_content"] = $this->load->view("manage_company_subbscription", $data, TRUE);
      $this->layout->mainSuperAdminTemplateTable($page);
	  
    }

        public function updatePlan($company_id){

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
              $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Subscription </strong>updated successfully</div>');
              redirect("superadmin/Managesubscription/index/".$company_id);
                    
           } else {
              $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000"><strong>Subscription </strong>not updated. Please try again.</div>');
              redirect("superadmin/Managesubscription/index/".$company_id);                    
              }        
        
       }  else {

          $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible" role="alert" data-auto-dismiss="4000">'.$stripe_response['msg'].'</div>');
          redirect("superadmin/Managesubscription/index/".$company_id);
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


     public function getTechCount($company_id){

         
       $where_check = array('company_id'=>$company_id,'role_id'=>4);
       $result = $this->Administrator->getNumberOFTechnician($where_check);
       
       echo json_encode(array('status' =>200 ,'result'=>$result->tech_count ));



    }

   


/*//////////////////////// Ajax Code End Here  ///////////// */

}
