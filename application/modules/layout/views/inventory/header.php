  <!-- Main navbar -->
  <div class="navbar navbar-inverse <?= $page_name; ?>">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><img src="<?php echo base_url('assets/admin/image/rsz_logo_color.png')?>" alt=""></a>

      <ul class="nav navbar-nav visible-xs-block">
        <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
      </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">
      <ul class="nav navbar-nav icon-dash" style="float: inline-start">
        <li><a class="sidebar-control sidebar-main-toggle hidden-xs" id="opentab"><i class="icon-paragraph-justify3"></i></a></li>
      </ul>

      <p class="navbar-text newdash"><span class="label page-name"><?= $page_name; ?></span></p>
         
       
<!-- <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="#"  >Add route</a></span>
                      </p> -->
         <?php 
            switch ($page_name) {
              case 'Dashboard-Inv':
            
              echo '  

             <p class="navbar-text btn-head"><span class="label btn-success "><a href="'.base_url('admin/Invoices').'" >Manage invoices</a></span>
                      </p>
                      <p class="navbar-text btn-head"><span class="label btn-warning "><a href="'.base_url('admin/reports').'" >View reports</a></span>
                      </p>
                      <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="'.base_url('admin/addProduct').'"  >Add product</a></span>
                      </p>
                      <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="'.base_url('admin/addCustomer').'" >Add customer</a></span>
                      </p>
                      
                      <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="'.base_url('admin/Estimates/addEstimate').'" >Create Estimate</a></span>
                      </p>
                      
                      ';
                
                break;
              
              case 'Customers':
              echo '
              <p class="navbar-text btn-head"><span class="label green-btn"><a href="'.base_url('admin/addCustomer').'" class="fas fa-plus";">Add Customer</a>
                    </span>
                      </p>
                   <p class="navbar-text btn-head"><span class="label btn-warning "><a href="#" data-toggle="modal" data-target="#modal_add_csv"  class="fas fa-upload">Bulk Upload</a>
                   </span>
                      </p>

                    <p class="navbar-text btn-head"><span class="label btn-primary "><a  href="'.base_url('uploads/sample_file/spray_customer.csv').'" class="fas fa-file-text-o">Sample File</a>
                   </span>
                      </p>
                   
                   <p class="navbar-text btn-head"><span class="label btn-primary "><a  href="'.base_url('admin/reports/customersCsv').'" class="fas fa-download"> CSV Download</a>
                   </span>
                      </p>

                      ';
                break;
              
             
            
               case 'Properties':
            echo ' <p class="navbar-text btn-head"><span class="label green-btn"><a href="'.base_url('admin/addProperty').'" class="fas fa-plus" >Add Property</a>
                   </span>
                      </p>
                   <p class="navbar-text btn-head"><span class="label btn-warning"><a href="#" data-toggle="modal" data-target="#modal_add_csv"  class=" fas fa-upload">Bulk Upload</a>
                  </span>
                      </p>

                  <p class="navbar-text btn-head"><span class="label btn-primary "><a  href="'.base_url('uploads/sample_file/spray_property.csv').'" class="fas fa-file-text-o" >Sample File</a>
                  </span>
                      </p>

                  <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="'.base_url('admin/Estimates/addEstimate').'" >Create Estimate</a></span>
                      </p>
                    <p class="navbar-text btn-head"><span class="label btn-primary "><a  href="'.base_url('admin/reports/propertiesCsv').'" class="fas fa-download"> CSV Download</a>
                   </span>
                      </p>
    
          
                  ';
              break;
            
            
             case 'Programs':
             echo '<p class="navbar-text btn-head"><span class="label green-btn"><a href="'.base_url('admin/addProgram').'" class="fas fa-plus">Add Program</a> </span>
                      </p>';
              break;
            
            case 'Products':
            echo '
            <p class="navbar-text btn-head"><span class="label green-btn"><a href="'.base_url('admin/addProduct').'" class="fas fa-plus" >Add Product</a>
                    </span>
                      </p>
                    <p class="navbar-text btn-head"><span class="label btn-warning "><a href="#" data-toggle="modal" data-target="#modal_add_csv"  class="nav-link btn-warning fas fa-upload">Bulk Upload</a>
                    </span>
                      </p>
                   <p class="navbar-text btn-head"><span class="label btn-primary"><a  href="'.base_url('uploads/sample_file/spray_product.csv').'" class="fas fa-file-text-o">Sample File</a>
                    </span>
                      </p>';
              break;
            
             case 'Services':
            echo '  <p class="navbar-text btn-head"><span class="label green-btn"><a href="'.base_url('admin/manageJobs').'">Manage Scheduled Services</a>
                    </span>
                      </p>
            <p class="navbar-text btn-head"><span class="label btn-primary"><a href="'.base_url('admin/job/addJob').'" class="fas fa-plus" >Add Service</a>
                    </span>
                      </p>
                     ';
              break;
               case 'Users':
            echo ' <p class="navbar-text btn-head"><span class="label green-btn"><a href="'.base_url('admin/users/addUser').'" class="fas fa-plus" style="background: rgb(41, 193, 168);">Add User</a>
                    </span>
                      </p>';
              break;
                case 'Invoices':
            echo '<p class="navbar-text btn-head"><span class="label green-btn "><a href="'.base_url('admin/Invoices/addInvoice').'" class="fas fa-plus"> New Invoice</a></span></p>
            <p class="navbar-text btn-head"><span class="label btn-primary "><a href="'.base_url('admin/reports/invoiceAgeReport').'" class="fas fa-file-text-o"> Invoice Age Report</a></span></p>
            <p class="navbar-text btn-head"><span class="label btn-primary "><a  href="'.base_url('admin/Invoices/downloadInvoiceCSV').'" class="fas fa-download"> CSV Download</a>
                   </span>
                      </p>';
              break;

             case 'Estimates':
            echo '<p class="navbar-text btn-head"><span class="label green-btn "><a href="'.base_url('admin/Estimates/addEstimate').'" class="fas fa-plus"> New Program Estimate(<span class="text-lowercase">s</span>)</a> </span></p>
                  <p class="navbar-text btn-head"><span class="label btn-primary "><a href="'.base_url('admin/Estimates/addServiceEstimate').'" class="fas fa-plus"> New Service Estimate</a> </span>
                  <p class="navbar-text btn-head"><span class="label btn-primary "><a href="'.base_url('admin/Estimates/bulkRenewalProgramsList').'" class="fas fa-plus"> Bulk Renewal</a> </span>
                      </p>
                    ';
              break;
              
         
           }

             ?>

            <?php 
                  $admindata =  $this->Administrator->getOneAdmin(array('user_id' =>$this->session->userdata['user_id']));                  
                  $admindata->user_pic = ($admindata->user_pic_resized != '') ? $admindata->user_pic_resized : $admindata->user_pic;        
                  
              ?>
      <ul class="nav navbar-nav navbar-right">
       
       <?php if($page_name=='Completed Service Log'  || $page_name=='Sales Tax Report' ) { ?>


                      <li class="dropdown language-switch">
                        <a class="dropdown-toggle" data-toggle="dropdown">
                                      Choose Report Type
                          <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu">

                          <li><a class="deutsch" href="<?= base_url('admin/reports') ?>" > Completed Service Log</a></li>
                          <li><a class="ukrainian" href="<?= base_url('admin/reports/salesTaxReport') ?>" > Sales Tax Report</a></li>

                        </ul>
                      </li>
                   

       <?php } ?>


       <li class="dropdown dropdown-user user-head">
          <a class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo CLOUDFRONT_URL.'uploads/profile_image/'.$admindata->user_pic ?>" alt="">
            <span class="usertext"  style="color: #01669a;"><?= $this->session->userdata['user_first_name'].' '.$this->session->userdata['user_last_name'] ?></span>
            <i class="caret"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-right">
           
             <li><a href="<?=  base_url('admin/users/updateProfile') ?>" >Profile</a></li>
                  <li><a href="<?= base_url('technician/dashboard/') ?>" >Switch to Technician View </a></li>
                  <li><a href="<?= base_url('admin/setting') ?>" >Settings </a></li>
                  <?php if ($this->session->userdata('subscription_details')->subscription_unique_id!=non_paid_sub_id) {
                   ?>
                    <li><a href="<?= base_url('admin/Managesubscription') ?>" >Manage Subscription</a></li>
                 <?php  } ?>
                  <li><a href="<?= base_url('admin/Logout') ?>" >Logout </a></li>

          </ul>
        </li>


      </ul>
    </div>
  </div>
  <!-- /main navbar -->


<script type="text/javascript">
 



$('#opentab').on('click', function() {
 if ($(".flex-item").hasClass('half-flex')) {
    $(".flex-item").removeClass('half-flex');
   $(".flex-item").addClass('full-flex');
  }
  else
  {
   $(".flex-item").addClass('half-flex');
   $(".flex-item").removeClass('full-flex');
  }

});


// on hover dropdown show
$(document).ready(function () {
    $('.user-head').hover(function () {
        $(this).find('.dropdown-menu').show();
    }, function () {
        $(this).find('.dropdown-menu').hide();
    });
});


</script>
