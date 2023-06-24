<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2LoginHelper;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Item;

class Quickbook extends MY_Controller {

   
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

        $this->load->model('AdminTbl_company_model', 'CompanyModel');
        $this->load->model('AdminTbl_customer_model', 'CustomerModel');
        $this->load->model('Invoice_model','INV'); 
        $this->load->helper('quickbook_helper'); 
        $this->load->helper('invoice_helper');
        $this->load->model('Invoice_sales_tax_model', 'InvoiceSalesTax');



    }



    public function checkConnection($value=''){

       $where = array(
         'company_id'=>$this->session->userdata['company_id'],
         'is_quickbook'=>1,
         'quickbook_status'=>1
      );
  
      $company_details = $this->CompanyModel->getOneCompany($where);
      if ($company_details) {
        echo 200;
      } else {
        echo 400;
      }
      
    }

    public function AuthSetInVariable($value='') {

        $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $this->input->post('quickbook_client_id'),
        'ClientSecret' => $this->input->post('quickbook_client_secret'),
        'RedirectURI' => 'https://dashboard.spraye.io/admin/quickbook/processCode',
        // 'RedirectURI' => 'https://dev-env.spraye.io/admin/quickbook/processCode',
        // 'RedirectURI' => 'https://spraye-dev8.blayzer.com/admin/quickbook/processCode',
        'scope' => 'com.intuit.quickbooks.accounting openid profile email phone address',
        'baseUrl' => "Production"
      ));

        $quicjbookArray = array(
            'quickbook_client_id' => $this->input->post('quickbook_client_id'),
            'quickbook_client_secret' => $this->input->post('quickbook_client_secret')
        );

        $this->session->set_userdata('quicjbookArray',$quicjbookArray);

         $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
         $authUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
         print_r($authUrl);
    }



function processCode()
{

    // Create SDK instance
    
    $dataService = DataService::Configure(array(
        'auth_mode' => 'oauth2',
        'ClientID' => $this->session->userdata('quicjbookArray')['quickbook_client_id'],
        'ClientSecret' =>  $this->session->userdata('quicjbookArray')['quickbook_client_secret'],
        'RedirectURI' => 'https://dashboard.spraye.io/admin/quickbook/processCode',
        //'RedirectURI' => 'https://dev-env.spraye.io/admin/quickbook/processCode',
        // 'RedirectURI' => 'https://spraye-dev8.blayzer.com/admin/quickbook/processCode',
        'scope' => 'com.intuit.quickbooks.accounting openid profile email phone address',
        'baseUrl' => "Production"
    ));

    $OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();
    $parseUrl = $this->parseAuthRedirectUrl($_SERVER['QUERY_STRING']);

    /*
     * Update the OAuth2Token
     */
    $accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($parseUrl['code'], $parseUrl['realmId']);
    $dataService->updateOAuth2Token($accessToken);

    /*
     * Setting the accessToken for session variable
     */

    $this->session->set_userdata('sessionAccessToken', $accessToken);

     $where = array('company_id' =>$this->session->userdata['company_id']);

     $param = array(

       'quickbook_client_id' => $this->session->userdata('quicjbookArray')['quickbook_client_id'],
       'quickbook_client_secret' => $this->session->userdata('quicjbookArray')['quickbook_client_secret'],
       'access_token_key' =>  $accessToken->getAccessToken(),
       'refresh_token_key' => $accessToken->getRefreshToken(),
       'qbo_realm_id' => $accessToken->getRealmID(),
       'is_quickbook' => 1,
       'quickbook_status' => 1     
     );
    
    $result = $this->CompanyModel->updateCompany($where,$param);

    $this->manageAlreadyQuickCustomer();
    $this->manageExitingCustomer();
    $this->manageAlreadyQuickInvoice();
    $this->manageExitingInvoices();

}

function parseAuthRedirectUrl($url){
    parse_str($url,$qsArray);
    return array(
        'code' => $qsArray['code'],
        'realmId' => $qsArray['realmId']
    );
}



public function testingCustomer($value=''){
     
    $company_details = $this->checkQuickbook();
    if ($company_details) {

      // echo "<pre>";
      // print_r($company_details);

      try{


             $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => 'AB9NkUpvle4qkrtdgISSWSA6Rkx4V2LZqh3V7aQ26uGS4hrKCe',
                    'ClientSecret' => '104OGelGKlWQXd0F77ejRlYErGqQfqF1AkEvE8Mj',
                    'accessTokenKey' => 'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..WENfbmSLrkidvA8tFb5aDQ.PMFdTfIbXzCQwMcsXX2tRwn_OwhPAkDxbIcbixce46tioBofCbpQ6pOlw_-1PJJs4vYDXIdduPbQBu9oCEcQR6nWvJ0AcaF_Asp_UURuvR8QW-LFOxMJXaPP5pa0Vnoyiv8uxk2flbuKIms73gfGKI9Ayktl_yf91DiSLQZSnNcgvUcJiiv6vZxgT1DB7vyfZ1UFY1hsDFS4IqCTrWT8NQvTzLmR7Qp-4zST57v0PsUN8wt-9VqqgdCOvrGJ7OX4TCD9ffbCHTWVwsUN8Jn_r_bkQInbSSLLORO-DcLHIcdBLzuQeiLRCtpqO1Wf6KnfSqiGOBXT2kUiRcVAsbgc1Jp0CFJSFLzJbjYM4QTg1_s0x8PQmfZXctJtJDb3MpGiJrdUZn77vv40QcjQUzEJcRWYNqKG-nEA0MMJ7b9T-YEGaMblbE7gTHJgpJl7g-tbq93Sn5b2iG8Vuk5P1J4Ew9BPWasPfN8jGOEWdWO2hJe6PeqTXpX4e6xlHWOj0hCTdyy9RidjahE8DQe8UIXD7z8JU-BHcC_ySLr18M-YH2wSrrz36xAhfibyCuIpakxN4Rv_2Q2hCZZfoy4ldY2yqx3Om6OTy6i17RmgLw25kHEVqtCQPwze0SvRECcI82JPTEXsJa5xWhjstdF6r8F_6-TqZW6nWZ0xns2FPPnwyP4jpXVUR8p9Eu-Fc4u4aYz0R_Z484AiKrJKbVC5JzmpYOJ47rqsCnBjUBf1ZUDsaFZOGYLNS2SytYOeZFHW9q2QZU7o8w_DQUBTvbrs0_zTyh9gsph4xXTlxi1cOalnGvVXhLKnQizeLtDtKtBeiJIrzDQPQKWj8j8xsw8lMUgC95CR7VfmPD8wszAEFy39IqmtXGvDGPurzQESRFYX2U-y.JDHBBt-SllU1aEzqZKohmw',
                    'refreshTokenKey' =>'AB11595337533bBELDKMUJ1QWnzpse9YYq2GgDHpEUJo3j0lte',
                    'QBORealmID' => '9130348192315236',
                    'baseUrl' => "Production"
             ));

             $email = 'hemantrajak1@gmail.com';

             $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");
             $entities = $dataService->Query("SELECT * FROM Customer where BillAddr.PostalCode ='".$email."'");
             $error = $dataService->getLastError();
             if ($error) {
                $return_error = '';
                $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                print_r($return_error);
                   
              } else {
                print_r($entities);
                echo "<br>";
                if(!empty($entities)) {
                  echo " found";

                } else {
                  echo "not found";
                } 

              }                   
            
            
       } catch (Exception $ex) {

          print_r($ex->getMessage());

       }

    } 
}


  
public function custCheckInQuickBook($dataService,$email=''){

  if ($email!='') {
   
      try{

          $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

          $entities = $dataService->Query("SELECT * FROM Customer where PrimaryEmailAddr ='".$email."'");
          $error = $dataService->getLastError();

          if ($error) {

                $return_error = '';
                $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                return false;   

          } else {

              if(!empty($entities)) {
                  return $entities[0]->Id;                        
              } else {
                 return false; 
              }
          }              

      } catch (Exception $ex) {
        return false;
      }
 
  } else {
 
    return false;
 
  }
}

public function manageAlreadyQuickCustomer(){
    

    $company_details = $this->checkQuickbook();

    if ($company_details) {

          try{

             $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $company_details->quickbook_client_id,
                    'ClientSecret' => $company_details->quickbook_client_secret,
                    'accessTokenKey' => $company_details->access_token_key,
                    'refreshTokenKey' =>$company_details->refresh_token_key,
                    'QBORealmID' => $company_details->qbo_realm_id,
                    'baseUrl' => "Production"
             ));

              $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

             $all_customer = $this->CustomerModel->get_all_customer_array(array('company_id' =>$company_details->company_id,'quickbook_customer_id !='=>0));

            if ($all_customer) {
                
              foreach ($all_customer as $key => $param) {

                $quickbook_customer_id =  $param['quickbook_customer_id'];

                  $entities = $dataService->Query("SELECT * FROM Customer where Id='".$quickbook_customer_id."'");
                  $error = $dataService->getLastError();
                  if ($error) {

                        $return_error = '';
                        $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                        $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                        $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";


                          $report_arr = array(
                            'company_id'=>$this->session->userdata['company_id'],
                            'response' => $return_error,
                            'date' => date("Y-m-d H:i:s")
                          );
                          quickBookDebugReport($report_arr);

     

                  } else {

                        if(!empty($entities)) {

                        } else {


                           $quickbook_customer_id_check = $this->custCheckInQuickBook($dataService,trim($param['email']));
                           if ($quickbook_customer_id_check) {
                              
                            $result = $this->CustomerModel->updateAdminTbl($param['customer_id'], array('quickbook_customer_id' =>$quickbook_customer_id_check ));

                           } else {

                               $customerObj = Customer::create([
                    
                                  "BillAddr" => [
                                     "Line1"=>  trim($param['billing_street']),
                                     "City"=>  trim($param['billing_city']),
                                     "Country"=>  "",
                                     "CountrySubDivisionCode"=>  "",
                                     "PostalCode"=>  trim($param['billing_zipcode'])
                                     ],
                                   "Notes" =>  "",
                                   "Title"=>  "",
                                   "GivenName"=>  trim($param['first_name']),
                                   "MiddleName"=>  "",
                                   "FamilyName"=>  trim($param['last_name']),
                                   "Suffix"=>  "",
                                   "FullyQualifiedName"=>trim($param['first_name']).' '.trim($param['last_name']),
                                   "CompanyName"=> isset($param['customer_company_name']) ? trim($param['customer_company_name']) : '',
                                   "DisplayName"=> trim($param['first_name']).' '.trim($param['last_name']),
                                   "PrimaryPhone"=>  [
                                       "FreeFormNumber"=> isset($param['phone']) ? trim($param['phone']) : '' 
                                   ],
                                   "PrimaryEmailAddr"=>  [
                                       "Address" =>  isset($param['email']) ? trim($param['email']) : ''
                                   ]


                                  ]);


                              $resultingCustomerObj = $dataService->Add($customerObj);
                              $error = $dataService->getLastError();
                              if ($error) {
                                  $return_error = '';
                                  $return_error = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                                  $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                                  $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";



                                    $report_arr = array(
                                      'company_id'=>$this->session->userdata['company_id'],
                                      'response' => $return_error,
                                      'date' => date("Y-m-d H:i:s")
                                    );
                                    quickBookDebugReport($report_arr);
       


                               } else {                
                                  
                                    $result = $this->CustomerModel->updateAdminTbl($param['customer_id'], array('quickbook_customer_id' =>$resultingCustomerObj->Id ));
                              }

                           }

                        } 

                    }   
                 
                
              }
            }     

          } catch (Exception $ex) {

           }

    } 
}




public function manageExitingCustomer(){
	
     $company_details = $this->checkQuickbook();

    if ($company_details) {


      try{
         $dataService = DataService::Configure(array(
                'auth_mode' => 'oauth2',
                'ClientID' => $company_details->quickbook_client_id,
                'ClientSecret' => $company_details->quickbook_client_secret,
                'accessTokenKey' => $company_details->access_token_key,
                'refreshTokenKey' =>$company_details->refresh_token_key,
                'QBORealmID' => $company_details->qbo_realm_id,
                'baseUrl' => "Production"
         ));

          $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

         $all_customer = $this->CustomerModel->get_all_customer_array(array('company_id' =>$company_details->company_id,'quickbook_customer_id'=>0));

        if ($all_customer) {



          foreach ($all_customer as $key => $param) {


             $quickbook_customer_id_check = $this->custCheckInQuickBook($dataService,trim($param['email']));

             if ($quickbook_customer_id_check) {


                $result = $this->CustomerModel->updateAdminTbl($param['customer_id'], array('quickbook_customer_id' =>$quickbook_customer_id_check ));

               
             } else {


                         // Add a customer
                      $customerObj = Customer::create([
                      
                      "BillAddr" => [
                         "Line1"=>  trim($param['billing_street']),
                         "City"=>  trim($param['billing_city']),
                         "Country"=>  "",
                         "CountrySubDivisionCode"=>  "",
                         "PostalCode"=>  trim($param['billing_zipcode'])
                         ],
                       "Notes" =>  "",
                       "Title"=>  "",
                       "GivenName"=>  trim($param['first_name']),
                       "MiddleName"=>  "",
                       "FamilyName"=>  trim($param['last_name']),
                       "Suffix"=>  "",
                       "FullyQualifiedName"=>trim($param['first_name']).' '.trim($param['last_name']),
                       "CompanyName"=> isset($param['customer_company_name']) ? trim($param['customer_company_name']) : '',
                       "DisplayName"=> trim($param['first_name']).' '.trim($param['last_name']),
                       "PrimaryPhone"=>  [
                           "FreeFormNumber"=> isset($param['phone']) ? trim($param['phone']) : '' 
                       ],
                       "PrimaryEmailAddr"=>  [
                           "Address" =>  isset($param['email']) ? trim($param['email']) : ''
                       ]


                      ]);


                  $resultingCustomerObj = $dataService->Add($customerObj);
                  $error = $dataService->getLastError();
                  if ($error) {
                      $return_error = '';
                      $return_error = "The Status code is: " . $error->getHttpStatusCode() . "\n";
                      $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                      $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";

                        $report_arr = array(
                          'company_id'=>$this->session->userdata['company_id'],
                          'response' => $return_error,
                          'date' => date("Y-m-d H:i:s")
                        );
                        quickBookDebugReport($report_arr);



                      // echo $return_error;  


                  } else {                
                      
                        $result = $this->CustomerModel->updateAdminTbl($param['customer_id'], array('quickbook_customer_id' =>$resultingCustomerObj->Id ));
                        // echo "Yes";
                        // echo "<br>";
                  }  

             }
            
          }
        } else {

          // $report_arr = array(
          //   'company_id'=>$this->session->userdata['company_id'],
          //   'response' => 'customer not found for upload in quick book '.$this->db->last_query(),
          //   'date' => date("Y-m-d H:i:s")
          // );
          // quickBookDebugReport($report_arr);

        }      
      } catch (Exception $ex) {

       }


    } 
}



public function manageAlreadyQuickInvoice($value=''){
     
    $company_details = $this->checkQuickbook();
    if ($company_details) {


      try{


             $dataService = DataService::Configure(array(
                    'auth_mode' => 'oauth2',
                    'ClientID' => $company_details->quickbook_client_id,
                    'ClientSecret' => $company_details->quickbook_client_secret,
                    'accessTokenKey' => $company_details->access_token_key,
                    'refreshTokenKey' =>$company_details->refresh_token_key,
                    'QBORealmID' => $company_details->qbo_realm_id,
                    'baseUrl' => "Production"
             ));

             $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

             $all_invoice = $this->INV->getAllInvoiveForQuick(array('invoice_tbl.company_id' =>$company_details->company_id,'quickbook_customer_id !='=>0, 'quickbook_invoice_id !='=>0, 'is_archived' => 0));

             if ($all_invoice) {

          

              foreach ($all_invoice as $key => $param) {

                  $invoice_id =  $param['invoice_id'];

                  $entities = $dataService->Query("SELECT * FROM Invoice where DocNumber='".$invoice_id."'");
                  $error = $dataService->getLastError();
                  if ($error) {
                    $return_error = '';
                    $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                    $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                    $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";


                      $report_arr = array(
                        'company_id'=>$this->session->userdata['company_id'],
                        'response' => $return_error,
                        'date' => date("Y-m-d H:i:s")
                      );
                      quickBookDebugReport($report_arr);



                     
                  } else {


                      if(!empty($entities)) {

                      } else {


                    $details = getVisIpAddr();


                    $all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$param['invoice_id']));
                    $line_ar = array();

                    $service_date =   $param['report_id'] != 0 ? date("m/d/Y", strtotime($param['invoice_created'])) : '';

                        $line_ar[] = array(
                           "Description" => $param['job_name'].' '.$service_date,
                           "Amount" => $param['cost'],
                           "DetailType" => "SalesItemLineDetail",
                           "SalesItemLineDetail" => array(
                             "TaxCodeRef" => array(
                               "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'
                                // "value" =>  'TAX'
                             )        
                           )
                        );

                        if ($all_sales_tax) {
                          
                          foreach ($all_sales_tax as $key => $value) {
                                $line_ar[] = array(
                                   "Description" =>  'Sales Tax: '.$value['tax_name'].' ('.floatval($value['tax_value']).'%) ',
                                   "Amount" => $value['tax_amount'],
                                   "DetailType" => "SalesItemLineDetail",
                                   "SalesItemLineDetail" => array(
                                     "TaxCodeRef" => array(
                                       "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'
                                        // "value" =>  'TAX'
                                     )        
                                   )
                                );
                          }

                        }


                        $invoice_arr = array(
							"AllowOnlineCreditCardPayment" => true,
                            "DocNumber" => $param['invoice_id'],
                            "TxnDate" => $param['invoice_date'],
                            "Line" =>$line_ar ,
                            "CustomerRef" => array(
                                "value" => $param['quickbook_customer_id'],
                            )
                        );

                        if ($param['email']!='') {

                            $invoice_arr['BillEmail'] = array(
                              "Address" => $param['email']
                            );
                            $invoice_arr['EmailStatus'] = "NeedToSend";
                        }

 
                         $theResourceObj = Invoice::create($invoice_arr);


                          $resultingObj = $dataService->Add($theResourceObj);


                          $error = $dataService->getLastError();
                          if ($error) {
                              $return_error ='';
                              $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                              $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                              $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";


                                  $report_arr = array(
                                    'company_id'=>$this->session->userdata['company_id'],
                                    'response' => $return_error,
                                    'date' => date("Y-m-d H:i:s")
                                  );
                                  quickBookDebugReport($report_arr);
     


                          }
                          else {

                               $result = $this->INV->updateInvoive(array('invoice_id'=>$param['invoice_id']), array('quickbook_invoice_id' =>$resultingObj->Id) );
                          }




                      


                      } 

                    }                   
              }
            }     
       } catch (Exception $ex) {

       }

    } 

       


  
}





public function manageExitingInvoices(){
      
    $company_details = $this->checkQuickbook();
    if ($company_details) {


        try{

               $dataService = DataService::Configure(array(
                      'auth_mode' => 'oauth2',
                      'ClientID' => $company_details->quickbook_client_id,
                      'ClientSecret' => $company_details->quickbook_client_secret,
                      'accessTokenKey' => $company_details->access_token_key,
                      'refreshTokenKey' =>$company_details->refresh_token_key,
                      'QBORealmID' => $company_details->qbo_realm_id,
                      'baseUrl' => "Production"
               ));

                $dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

                $all_invoice = $this->INV->getAllInvoiveForQuick(array('invoice_tbl.company_id' =>$company_details->company_id,'quickbook_customer_id !='=>0, 'quickbook_invoice_id'=>0, 'is_archived' => 0));

            

              if ($all_invoice) {



                // $report_arr = array(
                //   'company_id'=>$this->session->userdata['company_id'],
                //   'response' => 'invoice  get ready upload  in quick book'.$this->db->last_query(),
                //   'date' => date("Y-m-d H:i:s")
                // );
                // quickBookDebugReport($report_arr);



                foreach ($all_invoice as $key => $param) {

                    $quickbook_customer_id =  $param['quickbook_customer_id'];

                    $entities = $dataService->Query("SELECT * FROM Customer where Id='".$quickbook_customer_id."'");
                    $error = $dataService->getLastError();
                    if ($error) {
                      $return_error = '';
                      $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                      $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                      $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";
                    

                        $report_arr = array(
                          'company_id'=>$this->session->userdata['company_id'],
                          'response' => $return_error,
                          'date' => date("Y-m-d H:i:s")
                        );
                        quickBookDebugReport($report_arr);


                       
                    } else {

                      // echo "hi";
                        // print_r($entities);


                        if(!empty($entities)) {
                         
                           $details = getVisIpAddr();

                          
                        $all_sales_tax =  $this->InvoiceSalesTax->getAllInvoiceSalesTax(array('invoice_id'=>$param['invoice_id']));

                        $line_ar = array();

                        $service_date =   $param['report_id'] != 0 ? date("m/d/Y", strtotime($param['invoice_created'])) : '';

                        $line_ar[] = array(
                           "Description" => $param['job_name'].' '.$service_date,
                           "Amount" => $param['cost'],
                           "DetailType" => "SalesItemLineDetail",
                           "SalesItemLineDetail" => array(
                             "TaxCodeRef" => array(
                               "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'
                                // "value" =>  'TAX'
                             )        
                           )
                        );

                        if ($all_sales_tax) {
                          
                          foreach ($all_sales_tax as $key => $value) {
                                $line_ar[] = array(
                                   "Description" =>  'Sales Tax: '.$value['tax_name'].' ('.floatval($value['tax_value']).'%) ',
                                   "Amount" => $value['tax_amount'],
                                   "DetailType" => "SalesItemLineDetail",
                                   "SalesItemLineDetail" => array(
                                     "TaxCodeRef" => array(
                                       "value" =>  $details->geoplugin_countryCode=='IN' ? 3 : 'TAX'
                                        // "value" =>  'TAX'
                                     )        
                                   )
                                );
                          }

                        }


                        $invoice_arr = array(
							"AllowOnlineCreditCardPayment" => true,
                            "DocNumber" => $param['invoice_id'],
                            "TxnDate" => $param['invoice_date'],
                            "Line" =>$line_ar ,
                            "CustomerRef" => array(
                                "value" => $param['quickbook_customer_id'],
                            )
                        );

                        if ($param['email']!='') {

                            $invoice_arr['BillEmail'] = array(
                              "Address" => $param['email']
                            );
                            $invoice_arr['EmailStatus'] = "NeedToSend";
                        }



                           $theResourceObj = Invoice::create($invoice_arr);


                          $resultingObj = $dataService->Add($theResourceObj);


                            $error = $dataService->getLastError();
                            if ($error) {
                                $return_error ='';
                                $return_error .= "The Status code is: " . $error->getHttpStatusCode() . "\n";
                                $return_error .= "The Helper message is: " . $error->getOAuthHelperError() . "\n";
                                $return_error .= "The Response message is: " . $error->getResponseBody() . "\n";   


                                    $report_arr = array(
                                      'company_id'=>$this->session->userdata['company_id'],
                                      'response' => $return_error,
                                      'date' => date("Y-m-d H:i:s")
                                    );
                                    quickBookDebugReport($report_arr);
                                       

                          
                            }
                            else {           
                              
                                 $result = $this->INV->updateInvoive(array('invoice_id'=>$param['invoice_id']), array('quickbook_invoice_id' =>$resultingObj->Id) );
                   

                            }




                        } 

                      }                   
                }
              }
          
        } catch (Exception $ex) {
          
        }
    } 

       
}



 public function quickBookStatus(){
      
       $quickbook_status = $this->input->post('quickbook_status');
       $param = array('quickbook_status' =>$quickbook_status); 
       $where = array('company_id' =>$this->session->userdata['company_id']);
       $result =   $this->CompanyModel->updateCompany($where,$param);
       if ($quickbook_status==1) {
  
        $this->manageAlreadyQuickCustomer();
        $this->manageExitingCustomer();
        $this->manageAlreadyQuickInvoice();
  		  $this->manageExitingInvoices();
       }
         if ($result) {
            echo 1;
         } else {
           echo 0;                 
         }
    }

public function getAllCutomerFromQuickbook($value='')
{



// Prep Data Services
$dataService = DataService::Configure(array(
    'auth_mode' => 'oauth2',
    'ClientID' => "Q0DOicwl7UZ8X4tJZCFuBC3xwPmnucntdu6ybKBSa7UUL21VbM",
    'ClientSecret' => "8ZNrJnF2dJnDkdgsDOpwe6HqNS3EgqVQTynaeH68",
    'accessTokenKey' =>$this->session->userdata('sessionAccessToken')->getAccessToken(),
    'refreshTokenKey' => $this->session->userdata('sessionAccessToken')->getRefreshToken(),
    'QBORealmID' => $this->session->userdata('sessionAccessToken')->getRealmID(),
    'baseUrl' => "Production"
));

$dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

// Iterate through all Customers, even if it takes multiple pages
$i = 0;
while (1) {
    $allCustomers = $dataService->FindAll('Customer', $i, 500);
    $error = $dataService->getLastError();
    if ($error) {
        echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
        echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
        echo "The Response message is: " . $error->getResponseBody() . "\n";
        exit();
    }
    if (!$allCustomers || (0==count($allCustomers))) {
        break;
    }

    foreach ($allCustomers as $oneCustomer) {
        echo "Customer[".($i++)."]: {$oneCustomer->DisplayName}\n";
        echo "\t * Id: [{$oneCustomer->Id}]\n";
        echo "\t * Active: [{$oneCustomer->Active}]\n";
        echo "\n";
    }
}

 
}




  public function getAllInvoice($value=''){
   $company_details = $this->checkQuickbook();
   if ($company_details) {

		 $dataService = DataService::Configure(array(
		         'auth_mode' => 'oauth2',
		         'ClientID' => $company_details->quickbook_client_id,
		         'ClientSecret' => $company_details->quickbook_client_secret,
		         'accessTokenKey' => $company_details->access_token_key,
		         'refreshTokenKey' =>$company_details->refresh_token_key,
		         'QBORealmID' => $company_details->qbo_realm_id,
		         'baseUrl' => "Production"
		      ));

		$dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");




		$entities = $dataService->Query("SELECT * FROM Invoice");
		$error = $dataService->getLastError();
		if ($error) {
		    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
		    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
		    echo "The Response message is: " . $error->getResponseBody() . "\n";
		    exit();
		}

		echo "<pre>";
		print_r($entities);

	} else {

		echo "please  quickbook intigrate";
   	
    }
   	
   }




public function testing($value=''){
    echo "<pre>";
    $this->manageAlreadyQuickCustomer();
    $this->manageExitingCustomer();
    $this->manageAlreadyQuickInvoice();
    $this->manageExitingInvoices();

  
}



public function checkQuickbook() {
   $where = array(
     'company_id'=>$this->session->userdata['company_id'],     
     'is_quickbook'=>1,
     'quickbook_status'=>1
  );

   $company_details = $this->CompanyModel->getOneCompany($where);

   if ($company_details) {
	     try{

	        $oauth2LoginHelper = new OAuth2LoginHelper($company_details->quickbook_client_id,$company_details->quickbook_client_secret);  // clint id , clint sceter
	        $accessTokenObj = $oauth2LoginHelper->
	                            refreshAccessTokenWithRefreshToken($company_details->refresh_token_key);
	        $accessTokenValue = $accessTokenObj->getAccessToken();
	        $refreshTokenValue = $accessTokenObj->getRefreshToken();

	        $post_data = array(
	          'access_token_key' => $accessTokenValue, 
	          'refresh_token_key' => $refreshTokenValue,
	          

	          );

	           $this->CompanyModel->updateCompany($where,$post_data);
	  
	           $company_details->access_token_key = $accessTokenValue; 
	  
	           $company_details->refresh_token_key = $refreshTokenValue;

	          return $company_details;


	     } catch (Exception $ex) {
         
          // $report_arr = array(
          //   'company_id'=>$this->session->userdata['company_id'],
          //   'response' => $ex->getMessage(),
          //   'date' => date("Y-m-d H:i:s")
          // );
          // quickBookDebugReport($report_arr);

          return false;
	     }      


   } else {
      
      //  $report_arr = array(
      //   'company_id'=>$this->session->userdata['company_id'],
      //   'response' => 'quickbook not connected',
      //   'date' => date("Y-m-d H:i:s")
      // );
      // quickBookDebugReport($report_arr);

     return false;
   }

} 
 
    

}
