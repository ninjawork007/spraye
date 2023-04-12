<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Layout extends MY_Controller {

    public function __construct() {
        parent::__construct();
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
    public function index() {

    }

    public function superAdminTemplate($data = "") {
        $company_id = $this->session->userdata['company_id'];
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/superadmin/head', '', true);
        $header= $this->load->view('layout/superadmin/header',$header_data, true);
        $sidebar= $this->load->view('layout/superadmin/sidebar', $sideboar, true);
        $footer= $this->load->view('layout/superadmin/footer','', true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $data["temelate_footer"]=$footer;
        $this->load->view("layout/superadmin/main",$data);
     
    }
    
    public function superAdminTemplateTable($data = "") {
        $company_id = $this->session->userdata['company_id'];
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/superadmin/table_head', '', true);
        $header= $this->load->view('layout/superadmin/header', $header_data, true);
        $sidebar= $this->load->view('layout/superadmin/sidebar', $sideboar, true);
        $footer= $this->load->view('layout/superadmin/footer','', true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $data["temelate_footer"]=$footer;
        $this->load->view("layout/superadmin/main",$data);
    }

     public function superAdminInvoiceTemplateTable($data = "") {
        $company_id = $this->session->userdata['company_id'];
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/superadmin/table_invoice', '', true);
        $header= $this->load->view('layout/superadmin/header', $header_data, true);
        $sidebar= $this->load->view('layout/superadmin/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/superadmin/main",$data);
    }

    public function superAdminReportTemplateTable($data = "") {
        $company_id = $this->session->userdata['company_id'];
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/superadmin/table_report', '', true);
        $header= $this->load->view('layout/superadmin/header', $header_data, true);
        $sidebar= $this->load->view('layout/superadmin/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/superadmin/main",$data);
    }
    
    public function superAdminTemplateEditor($data = "") {
       
        $company_id = $this->session->userdata['company_id'];
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/superadmin/editor_head', '', true);
        $header= $this->load->view('layout/superadmin/header',$header_data, true);
        $sidebar= $this->load->view('layout/superadmin/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/superadmin/main",$data);
    }
	
	public function technicianTemplate($data = "") {
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
         $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/technician/head', '', true);
        $header= $this->load->view('layout/technician/header',$header_data, true);
        $sidebar= $this->load->view('layout/technician/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/technician/main",$data);
     
    }
    
    public function technicianTemplateTable($data = "") {
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/technician/table_head', '', true);
        $header= $this->load->view('layout/technician/header', $header_data, true);
        $sidebar= $this->load->view('layout/technician/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/technician/main",$data);
    }
	
	public function technicianTemplateTableDash($data = "") {
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/technician/table_head', '', true);
        //  $head= $this->load->view('layout/superadmin/table_head', '', true);
        $header= $this->load->view('layout/technician/header', $header_data, true);
        $sidebar= $this->load->view('layout/technician/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/technician/tech_main",$data);
    }

    public function mainSuperAdminTemplateTable($data = "") {
        $company_id = $this->session->userdata['company_id'];
        $sideboar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/mainsuperadmin/table_head', '', true);
        $header= $this->load->view('layout/mainsuperadmin/header', $header_data, true);
        $sidebar= $this->load->view('layout/mainsuperadmin/sidebar', $sideboar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        $this->load->view("layout/mainsuperadmin/main",$data);    
    }

    #### ADDED CUSTOMER TEMPLATE FOR CUSTOMER PORTAL ####
    public function customersTemplateTable($data = "") {
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/customers/table_head', '', true);
        $header= $this->load->view('layout/customers/header',$header_data, true);
        $footer= $this->load->view('layout/customers/footer','', true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_footer"]=$footer;
        $this->load->view("layout/customers/main",$data);

    }

    #### ADDED INVENTORY TEMPLATE FOR INVENTORY ####
    public function inventoryTemplateTable($data = "") {
        $company_id = $this->session->userdata['company_id'];
        $sidebar["active_sidebar"]= isset($data["active_sidebar"])?$data["active_sidebar"]:"";
        $header_data["page_name"]= isset($data["page_name"])?$data["page_name"]:"";
        $head= $this->load->view('layout/superadmin/table_invoice', '', true);
        $header= $this->load->view('layout/superadmin/header', $header_data, true);
        $sidebar= $this->load->view('layout/superadmin/sidebar', $sidebar, true);
        // $head= $this->load->view('layout/inventory/table_invoice', '', true);
        // $header= $this->load->view('layout/inventory/header', $header_data, true);
        // $sidebar= $this->load->view('layout/inventory/sidebar', $sidebar, true);
        $data["temelate_head"]=$head;
        $data["temelate_header"]=$header;
        $data["temelate_sidebar"]=$sidebar;
        // $this->load->view("layout/inventory/main",$data);
        $this->load->view("layout/superadmin/main",$data);
    }

}