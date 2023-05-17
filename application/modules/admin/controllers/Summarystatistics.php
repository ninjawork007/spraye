<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * SummaryStatistics
 * This class is intended to be used as a controller
 * to server AJAX / API calls from front end javascript
 * providing JSON response with summary statistics shown
 * on the admin dashboard.
 * At time of writing, this includes:
 *  - number of scheduled services
 *  - number of services to be re-scheduled
 *  - number of completed services
 *  - etc
 *
 */
class Summarystatistics extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('email'))
        {return redirect('admin/auth');}

        if ( !$this->session->userdata('spraye_technician_login'))
        {return redirect('admin/auth');}

    }


    /**
     * getJobCount
     * @return null
     *   This function returns the number of scheduled services.
     *   The default timeframe is set to a month,
     *   which will return a number based on the current month and current year.
     *
     *   Example access using localhost
     *   https://localhost/admin/summarystatistics/getJobCount/month
     *
     *   Query not optimised, just lifted and shifted from
     *   file "public/application/helpers/job_helper.php"
     */
    public function getJobCount($timeframe='month')
    {
        $company_id = $this->session->userdata['company_id'];
        $this->db->select("technician_job_assign_id,invoice_id,first_name,last_name,program_name,customers.customer_id,category_area_name,is_job_mode,job_name,user_first_name,user_last_name,job_assign_date,property_address,property_title,technician_id");
        $this->db->from('technician_job_assign');
        $this->db->join('customers','customers.customer_id = technician_job_assign.customer_id ','inner');
        $this->db->join('programs','programs.program_id = technician_job_assign.program_id','inner');
        $this->db->join('property_tbl','property_tbl.property_id = technician_job_assign.property_id','inner');
        $this->db->join('category_property_area','category_property_area.property_area_cat_id = property_tbl.property_area','left');
        $this->db->join('jobs','jobs.job_id=technician_job_assign.job_id','inner');
        $this->db->join('users','users.user_id=technician_job_assign.technician_id','inner');
        $this->db->where("technician_job_assign.company_id = '".$company_id."'");
        $this->db->where("is_job_mode = '0'");
        $this->db->order_by('job_assign_date DESC, technician_id ASC');
        //var_dump($this->db->get_compiled_select());
		//$this->db->limit(100);
        $result = $this->db->get();
        $data = $result->result();

        echo json_encode(array('status' =>200 ,'result'=>count($data) ));
    }


    /**
     * getCompletedJobCount
     * @return null
     *   This function returns the number of completed job.
     *   The default timeframe is set to a month,
     *   which will return a number based on the current month and current year.
     *
     *   Example access using localhost
     *   https://localhost/admin/summarystatistics/getCompletedJobCount/month
     *
     *   Query not optimised, just lifted and shifted from
     *   file "public/application/helpers/job_helper.php"
     */
    public function getCompletedJobCount($timeframe='month')
    {
        if($timeframe=='month'){
            $company_id = $this->session->userdata['company_id'];
            $query = $this->db->query("SELECT count(*) count FROM `report` WHERE    `company_id` = $company_id and  MONTH(`job_completed_date`) = ".date('m')." and   YEAR(`job_completed_date`) = ".date('Y')."  ");
            $result = $query->row();

            echo json_encode(array('status' =>200 ,'result'=>$result->count ));
            return null;
        }

        echo json_encode(array('status' =>404));
    }


    /**
     * getCountOfRescheduledJobs
     * @return void
     *
     * This function returns the number of re-scheduled jobs.
     *
     * Example usage using localhost
     * https://localhost/admin/summarystatistics/getCountOfRescheduledJobs
     *
     *  Query not optimised, just lifted and shifted (then deleted) from
     *  file "public/application/modules/admin/controllers/Admin.php"
     *  index method (ie, dashboard main page "localhost/admin").
     */
    public function getCountOfRescheduledJobs()
    {
        // load tech model
        $this->load->model('Technician_model', 'Tech');
        $where_arr = [
            'technician_job_assign.company_id' => $this->session->userdata['company_id'],
            'technician_job_assign.is_job_mode'=>2,
        ];
        $result = $this->Tech->GetCountOfRescheduledJobs($where_arr);

        echo json_encode(array('status' =>200 ,'result'=>$result ));
    }

}
