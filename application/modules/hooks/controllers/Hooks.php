<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

ini_set('max_execution_time', 300);
require APPPATH . '/third_party/smtp/Send_Mail.php';
require_once APPPATH . '/third_party/GCM/androidNoti.php';

/**
 * Description of Hooks
 *
 * @author satanand
 */
class Hooks extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('HooksModel');
        $this->load->helper('file');
    }

    public function sendVerificationMail() {
        $post = $this->input->post();
        $content = $this->load->view('hooks/welcome', "", TRUE);
        $content = str_replace("[Product Name]", "HoopApp Loyalty", $content);
        $content = str_replace("{{name}}", $post['full_name'], $content);
        $content = str_replace("{{username}}", $post['username'], $content);
        $content = str_replace("{{action_url}}", base_url('hooks/verifyMail/') . $post['email_link'], $content);


        Send_Mail($post['email'], "noreplay@testmail.com", $content, "Welcome to Daddy Pocket, " . $post['full_name'] . "!");
    }

    public function passwordResetLink() {
        $post = $this->input->post();
        //var_dump($post);die;
        $content = $this->load->view('hooks/password_reset', '', TRUE);
        $content = str_replace("{{name}}", $post['full_name'], $content);        
        $content = str_replace("{{action_url}}", base_url('hooks/resetPassword/' . $post['password_reset_link']), $content);
        Send_Mail($post['email'], "noreplay@daddypocket.com", $content, "Daddy Pocket: Request for a new password!");
    }

    public function helpCenterMail() {
        $post = $this->input->post();
        //var_dump($post);die;
        $content = $this->load->view('hooks/help_center', '', TRUE);
        $content = str_replace("{{name}}", $post['full_name'], $content);        
        $content = str_replace("{{email}}", $post['email'], $content);
        $content = str_replace("{{important_type}}", $post['important_type'], $content);        
        $content = str_replace("{{message_type}}", $post['message_type'], $content);
        $content = str_replace("{{subject}}", $post['subject'], $content);        
        $content = str_replace("{{message}}", $post['message'], $content);
        Send_Mail($post['email'], "noreplay@daddypocket.com", $content, "Daddy Pocket: Help Center Request");
    }

    public function passwordResetLinkAdmin() {
        $post = $this->input->post();
//        $post=array(
//            "full_name"=>"satanand tiwari",
//            "password_reset_link"=>"sdaf", 
//            "email"=>"satanand.tiwari@canopusinfosystems.com" 
//            
//        );
//        var_dump($post);die;
        $content = $this->load->view('hooks/password_reset', '', TRUE);
        $content = str_replace("{{name}}", $post['full_name'], $content);
        $content = str_replace("[Product Name]", "HoopApp Loyalty", $content);
        $content = str_replace("{{action_url}}", base_url('superadmin/auth/resetPassword/' . $post['password_reset_link']), $content);
        Send_Mail($post['email'], "noreplay@testmail.com", $content, "HoopApp Loyalty: Request for a new password!");
    }

    public function error() {
        $this->load->view('hooks/errorPage');
    }

    public function resetPassword($link = "") {
        if ($this->input->post()) {

            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('cpassword', 'Confirm password', 'required|trim|matches[password]');
            $this->form_validation->set_message('matches', "Password do not match");
            $this->form_validation->set_message('required', "%s is required.");
            $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
            if ($this->form_validation->run() == FALSE) {
                $customer = $this->HooksModel->getCoustomerOne(array('password_reset_link' => $link));
                $data['customer'] = $customer;
                $this->load->view('hooks/resetPassword', $data);
            } else {
                $param = array(
                    'password_reset_link' => "",
                    'password' => md5($this->input->post('password')),
                );
                if ($this->HooksModel->updateCoustomer(array('customer_id' => $this->input->post('customer_id')), $param)) {
                    $data['heading'] = "Password Changed";
                    $data['message'] = "Your password has been changed successfully. Now you can login with your new password. <b>Thank you</b>";
                    $this->load->view('hooks/successPage', $data);
                } else {
                    return redirect('hooks/error');
                }
            }
        } else {
            if ($link == "") {
                return redirect('hooks/error');
            } else {
                $customer = $this->HooksModel->getCoustomerOne(array('password_reset_link' => $link));
                if ($customer) {
                    if (strtotime($customer->password_link_expire) > strtotime(Date('Y-m-d H:i:s'))) {
                        $data['customer'] = $customer;
                        $this->load->view('hooks/resetPassword', $data);
                    } else {
                        $data['heading'] = "Link expired";
                        $data['message'] = "Your password reset link has been expired. Please try again to get a new link. <b>Thank you</b>";
                        $this->load->view('hooks/successPage', $data);
                    }
                } else {
                    return redirect('hooks/error');
                }
            }
        }
    }

    public function verifyMail($link = "") {
        if ($link == "") {
            return redirect('hooks/error');
        } else {
            $customer = $this->HooksModel->getCoustomerOne(array('email_link' => $link));
            //var_dump($customer);die;
            if ($customer) {
                if (strtotime($customer->password_link_expire) > strtotime(Date('Y-m-d H:i:s'))) {
                    $param = array(
                        'email_verified' => 1,
                        'email_link' => "",
                    );
                    if ($this->HooksModel->updateCoustomer(array('customer_id' => $customer->customer_id), $param)) {
                        $data['heading'] = "Email address successfully verified";
                        $data['message'] = "Your email address successfully verified. <br><b>Thank you</b>";
                        $this->load->view('hooks/successPage', $data);
                    } else {
                        return redirect('hooks/error');
                    }
                } else {
                    $this->HooksModel->deleteCustomer(array('customer_id' => $customer->customer_id));
                    $data['heading'] = "Link expired";
                    $data['message'] = "Your email verification link has been expired. Please try again to get a new link. <b>Thank you</b>";
                    $this->load->view('hooks/successPage', $data);
                }
            } else {
                return redirect('hooks/error');
            }
        }
    }

    public function passwordResetLinkMerchant() {
        $post = $this->input->post();
        //var_dump($post);die;
        $content = $this->load->view('hooks/password_reset', '', TRUE);
        $content = str_replace("{{name}}", $post['full_name'], $content);
        $content = str_replace("[Product Name]", "HoopApp Loyalty", $content);
        $content = str_replace("{{action_url}}", base_url('merchant/auth/resetPassword/' . $post['password_reset_link']), $content);
        Send_Mail($post['email'], "noreplay@testmail.com", $content, "HoopApp Loyalty: Request for a new password!");
    }

    private function filewrite($data) {
        if (!write_file('notification_test.txt', $data)) {
            echo 'Unable to write the file';
        } else {
            echo 'File written!';
        }
    }

    public function smileyNotification() {
        $post = $this->input->post();
        $user_radar_id = $post['user_radar_id'];
        $to_user_radar_id = $post['to_user_radar_id'];
        $hotspot_id = $post['hotspot_id'];
        $userDetails = $this->HooksModel->getOneUserDetails(array('user_radar_id' => $user_radar_id));
        $deviceDetails = $this->HooksModel->getAllUserAuth(array('user_radar_id' => $to_user_radar_id));

        $checkSmile = $this->HooksModel->getOneSmilyData(array('user_radar_id' => $to_user_radar_id, 'to_user_radar_id' => $user_radar_id));
        $checkSmileList = $this->HooksModel->getAllSmilyData(array('to_user_radar_id' => $user_radar_id));



        foreach ($deviceDetails as $device) {
            $title = "Radar App";
            if ($device->device_type == "ios") {
                if ($checkSmile) {
                    if ($checkSmileList > 1) {
                        // $body = "{$userDetails->first_name} and {$checkSmileList} others smiled back at you!";
                        $body = '😍 '."Someone smiled back at you!";
                        $message = array("title" => $title, "body" => $body, "name"=>$userDetails->first_name, "launch-image" => $userDetails->profile_pic);
                    } else {
                        // $body = "{$userDetails->first_name} smiled back at you!";
                        $body = '😍 '."Someone smiled back at you!";
                        $message = array("title" => $title, "body" => $body, "name"=>$userDetails->first_name, "launch-image" => $userDetails->profile_pic);
                    }
                } else {
                    if ($checkSmileList) {
                        // $body = "{$userDetails->first_name} and {$checkSmileList} others smiled at you!";
                        $body = '😊 '."Someone smiled at you!";
                        $message = array("title" => $title, "body" => $body, "name"=>$userDetails->first_name, "launch-image" => $userDetails->profile_pic);
                    } else {
                        // $body = "{$userDetails->first_name} smiled at you!";
                        $body = '😊 '."Someone smiled at you!";
                        $message = array("title" => $title, "body" => $body, "name"=>$userDetails->first_name, "launch-image" => $userDetails->profile_pic);
                   }
                }
                $param = array(
                    "title" => $title,
                    "body" => $body,
                    "user_radar_id" => $user_radar_id,
                    "to_user_radar_id" => $to_user_radar_id,
                    "hotspot_id"=>$hotspot_id,
                    "name"=>$userDetails->first_name,
                    "notification_for" => "smiley",
                    "created_at" => Date("Y-m-d H:i:s") 
                );
                $this->HooksModel->saveNotification($param);

                $this->iosNotification($device->device_token, $message, FALSE);
            } else {
                
            }
        }
    }

    public function testIosNotificaton($dt = false) {
//        $deviceToken = "810F291D10C8ACAE5459BD940B6C5905F75C3AE93F21B9A504515372303E0D2B";
        $deviceToken = "5A507E064C5634CB8878F09355AC8816AA7404517AADA522AF8948C29A872DD6";
        if ($dt) {
            $devicetoken = $dt;
        }

        //http://111.118.246.35/radarapp/uploads/profile_image/1099e720d6113e935b9876065f7e0a3c.png
        $message = array("title" => "This is title", "body" => "this is body", "launch-image" => base_url('uploads/profile_image/1099e720d6113e935b9876065f7e0a3c.png'));
        $extra = "";
        $this->iosNotification($deviceToken, $message, FALSE);
    }

    private function iosNotification($deviceToken = "", $message = "", $extra = FALSE) {
        //echo "sadf";die;
        // $this->load->library("APN");


        $this->load->library('apn');
        $this->apn->payloadMethod = 'simple'; // включите этот метод для отладки
        //$this->apn->payloadMethod = 'enhance'; // включите этот метод для отладки
        $this->apn->connectToPush();

        // добавление собственных переменных в notification
        $this->apn->setData(array('someKey' => true));
        // $this->apn->expiry=86400;
        $send_result = $this->apn->sendMessage($deviceToken, $message, 0, 'default', "", FALSE, $extra);
        //var_dump($send_result);die;
        if ($send_result)
            log_message('debug', 'Отправлено успешно');
        else
        //echo $this->apn->error;
            log_message('error', $this->apn->error);


        return $this->apn->disconnectPush();
    }

    public function getNearByPlaces() {
//        $client_id = "LNDLLDJCTLQFDWIGMKV1VYRYAP3R5REG5LXO03JZ23IB4VEB";
//        $client_secret = "A5IZJP5OL2TY1TQEGCV5CIRHQIRHKCKQLB15LY4HBUSEQKIZ";
        $client_id = "YJ4GXBD5ANZDY2GX3UCOFMITC5DJVRI5WIYFQ3CPLHQ0UQ0J";
        $client_secret = "NN3UQDV5YBHZZ4O2I0P1ISNO1PVDF2NYJJY1IGGR4CPZQTGU";
        $redirect_url = "http://111.118.246.35/radarapp/hooks/getAuthToken2";
//        35AFOPBYRIYAVQP4KPKMNN40OHUCA2CWE1VLLQZRAZNEIFOW
//        $auth_token = "XU5SOCWNC3PSORK2IFBDXZPK3TX2N2QYX3IEYEYXIA1UPN04";
        $foursquare = new FoursquareApi($client_id, $client_secret);
        echo $auth_link = $foursquare->AuthenticationLink($redirect_url);
//        $endpoint = "venues/search";
    }

    public function getAuthToken() {
//        $client_id = "LNDLLDJCTLQFDWIGMKV1VYRYAP3R5REG5LXO03JZ23IB4VEB";
//        $client_secret = "A5IZJP5OL2TY1TQEGCV5CIRHQIRHKCKQLB15LY4HBUSEQKIZ";
        $client_id = "YJ4GXBD5ANZDY2GX3UCOFMITC5DJVRI5WIYFQ3CPLHQ0UQ0J";
        $client_secret = "NN3UQDV5YBHZZ4O2I0P1ISNO1PVDF2NYJJY1IGGR4CPZQTGU";

        $redirect_url = "http://111.118.246.35/radarapp/hooks/getAuthToken";
        $foursquare = new FoursquareApi($client_id, $client_secret);
        $code = $this->input->get('code');
        $token = $foursquare->GetToken($code, $redirect_url);
        var_dump($token);


        // code VAVXZRHTTGKGQKVGQOI5B42URI1LDHQHLEKZ1S03CQBGBXBJ#_=_
        // Auth token  XU5SOCWNC3PSORK2IFBDXZPK3TX2N2QYX3IEYEYXIA1UPN04
    }

    public function deleteAllCanopusUser($email = false) {
        if ($email) {
            $this->db->where("email LIKE '%$email%'")->delete('tbl_user');
            echo "<h1>Oops</h1>";
            echo "<h1>$email is deleted........</h1>";
        } else {
            echo "@ ke pahle wali string pass karo data delete ho jayega email ka. E.g. testcanopus27@gmail.com ke liye testcanopus27 ";
        }
    }

    public function callback() {
        $session = new \SpotifyWebAPI\Session(
                'e56d2dbab61041c5bd6cdacdfee3c364', '99abe26fbe944a86ace059ce3669dfec', 'http://111.118.246.35/radarapp/hooks/callback'
        );
        $authtoken="BQDt0XRxvJf5YS4lPoKBJKzxSj56hoP-j13gRVMEQcp7L3jSV7nscG9_vi0idqYD29s7oepkiInHgBhesZHEcW1GCz4otwOmytzpqXSXtqYKJJS0vpl2vJnQ7LDGJOkb4FVKMcjZ8-Vrlr_HLKyfcyq7sQZEnXr3bg";
         // url to refresh token
        // https://accounts.spotify.com/authorize?redirect_uri=http://111.118.246.35/radarapp/hooks/callback&client_id=e56d2dbab61041c5bd6cdacdfee3c364&response_type=code
        $code = $this->input->get('code');
        //echo $this->input->get('state');   
        $api = new \SpotifyWebAPI\SpotifyWebAPI();
        $session->requestAccessToken($code);
        $aT= $session->getAccessToken();
        $this->db->insert('tbl_spotify_access_token',array('access_token'=>$aT));    
       // $api->setAccessToken($authtoken);    
        
      // $response=$api->search("AR", "artist");
//        var_dump($response);
        
       // echo json_encode($response,JSON_PRETTY_PRINT);
        //
//        echo "<img src='https://i.scdn.co/image/5bb34c7ecca1f85b1859f03244b7c9f327b2765b'/>";
//        print_r($api->me());
    }

    public function newcallback() {
        $session = new SpotifyWebAPI\Session(
                'e56d2dbab61041c5bd6cdacdfee3c364', '99abe26fbe944a86ace059ce3669dfec', 'http://111.118.246.35/radarapp/hooks/callback'
        );
        
        
    }

}
