  <!-- Main navbar -->
  <div class="navbar navbar-inverse <?= $page_name; ?>">
    <div class="navbar-header">
      <a class="navbar-brand" href="#"><img src="<?php echo base_url('assets/admin/image/rsz_logo_color.png')?>" alt=""></a>
      
      <div class="visible-xs-block" style="float: left;" >
         <p class="navbar-text newdash"><span class="label page-name"><?= $page_name; ?></span></p>        
      </div> 

      <ul class="nav navbar-nav visible-xs-block">       
        <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
      </ul>
    </div>


    <div class="navbar-collapse collapse" id="navbar-mobile">
      <ul class="nav navbar-nav icon-dash" style="float: inline-start">
        <li><a class="sidebar-control sidebar-main-toggle hidden-xs" id="opentab"><i class="icon-paragraph-justify3"></i></a></li>
      </ul>


      <p class="navbar-text newdash btn-hide"><span class="label page-name"><?= $page_name; ?></span></p>
         
       
<!-- <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="#"  >Add route</a></span>
                      </p> -->
         <?php
            // $test = 'blah';
            // if($setting_details->is_tech_vehicle_inspection_required) {
            //   $test = 'true';
            // } elseif($setting_details->is_tech_vehicle_inspection_required === '0') {
            //   $test = 'false';
            // }
            // die( print_r( $test,true ));

            switch ($page_name) {
              case 'Day at a Glance':
              $class1 =   !empty($job_assign_details) ? 'start_day' : '';
              $class2 =   !empty($job_assign_details) ? 'next_stop' : '';
              $style = empty($job_assign_details) ? 'display:none;' : '';

              if($is_first_job)
              {
                echo '  
                    <p class="btn-hide navbar-text btn-head"><span class="label green-btn ">
                      <a data-toggle="modal" href="#modal_vehicle_inspection" id="start_inspection">START INSPECTION</a></span>  
                    </p>                  
                    <p class="btn-hide navbar-text btn-head" style="display:none;"><span class="label green-btn ">
                      <a href="#" class="'.$class1.'" id="start-day-btn">START DAY</a></span>
                    </p>
                    <p class="btn-hide navbar-text btn-head" style="display:none;"><span class="label green-btn ">
                      <a href="#" class="'.$class2.'" id="next-stop-btn">NEXT STOP</a></span>
                    </p>
                    <p class="btn-hide navbar-text btn-head"><span class="label" style="background-color:#FFBE2C;">
                      <a data-toggle="modal" class="vehicle_issue fas fa-plus" href="#modal_vehicle_issue" id="start_vehicle_issue">VEHICLE ISSUE </a>
                    </span></p>
                    <p class="btn-hide navbar-text btn-head"><span class="label" style="background-color:#ef6c00;display:none;"><a class="finishday fas fa-plus" id="finish-day-btm">FINISH DAY</a></span>
                    </p>


                ';
              } else {

                echo '  
                    <p class="btn-hide navbar-text btn-head" style="display:none;"><span class="label green-btn ">
                      <a data-toggle="modal" href="#modal_vehicle_inspection" id="start_inspection">START INSPECTION</a></span>  
                    </p>                  
                    <p class="btn-hide navbar-text btn-head" style="display:none;"><span class="label green-btn ">
                      <a href="#" class="'.$class1.'" id="start-day-btn">START DAY</a></span>
                    </p>
                    <p class="btn-hide navbar-text btn-head" style="'.$style.'">><span class="label green-btn ">
                      <a href="#" class="'.$class2.'" id="next-stop-btn">NEXT STOP</a></span>
                    </p>
                    <p class="btn-hide navbar-text btn-head" style=""><span class="label" style="background-color:#FFBE2C;">
                      <a data-toggle="modal" class="vehicle_issue fas fa-plus" href="#modal_vehicle_issue" id="start_vehicle_issue">VEHICLE ISSUE </a>
                    </span></p>
                    <p class="btn-hide navbar-text btn-head"><span class="label" style="background-color:#ef6c00;"><a class="finishday fas fa-plus" id="finish-day-btm">FINISH DAY</a></span>
                    </p>


                ';

              }
                  

            


                break;
                // case 'Add Estimate':
                //   echo '
                //         <p class="navbar-text btn-head"><span class="label btn-primary "><a href="'.base_url('technician/Estimates/addServiceEstimate').'" class="fas fa-plus"> New Service Estimate</a> </span>
                //         <p class="navbar-text btn-head"><span class="label btn-primary "><a href="'.base_url('technician/Estimates/bulkRenewalProgramsList').'" class="fas fa-plus"> Bulk Renewal</a> </span>
                //             </p>
                         
                //           ';
                //     break;
              
              
            }
             ?>

            <?php 
                   $admindata =  $this->Administrator->getOneAdmin(array('user_id' =>$this->session->userdata['spraye_technician_login']->user_id));
                   $admindata->user_pic = ($admindata->user_pic_resized != '') ? $admindata->user_pic_resized : $admindata->user_pic;

                  
              ?>
      <ul id="open-bx" class="nav navbar-nav navbar-right">
       


       <li class="dropdown dropdown-user user-head">
          <a class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo CLOUDFRONT_URL.'uploads/profile_image/'.$admindata->user_pic ?>" alt="">
            <span class="usertext"  style="color: #01669a;"><?= $admindata->user_first_name.' '.$admindata->user_last_name ?></span>
            <i class="caret"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-right">
           
             <li><a href="<?=  base_url('technician/updateProfile') ?>" >Profile</a></li>
           <?php  if ($this->session->userdata('role_id')!= 4) {  ?>
                  <li><a href="<?= base_url('admin') ?>" >Switch to admin View </a></li>
                  <?php } ?>
                  <li><a href="<?= base_url('technician/Logout') ?>" >Logout </a></li>

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

  $('#open-bx').on('click', function() {
    $(".user-head").toggleClass("open");
  });
  $('.user-head').hover(function () {

   
        $(this).find('.dropdown-menu').show();
    }, function () {
        $(this).find('.dropdown-menu').hide();
    });







</script>
