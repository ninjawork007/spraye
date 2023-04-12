<?php

error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Sheduling extends MY_Controller {

    const FS_client_id = "LNDLLDJCTLQFDWIGMKV1VYRYAP3R5REG5LXO03JZ23IB4VEB";
    const FS_client_secret = "A5IZJP5OL2TY1TQEGCV5CIRHQIRHKCKQLB15LY4HBUSEQKIZ";
    const FS_redirect_url = "http://111.118.246.35/radarapp/hooks/getAuthToken";
    const FS_auth_token = "XU5SOCWNC3PSORK2IFBDXZPK3TX2N2QYX3IEYEYXIA1UPN04";

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
        $this->load->model("Administrator");
        $this->load->library('form_validation');
    }

   

    public function index() {
       $page["active_sidebar"] = "shedulingnav";
        $page["page_content"] = $this->load->view("admin/sheduling", $data, TRUE);
        $this->layout->superAdminTemplate($page);
    }

 
 
    

}
