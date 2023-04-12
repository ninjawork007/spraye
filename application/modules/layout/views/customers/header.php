  <!-- Main navbar -->
  <div class="navbar navbar-inverse <?= $page_name; ?>">
    <div class="navbar-header">
      <?php
        if($this->session->userdata('company_logo')){
      ?>
      <a class="navbar-brand" href="<?= base_url('customers/dashboard/').$_SESSION['customer_id'] ?>"><img src="https://assets-dashboard.spraye.io/uploads/company_logo/<?= $this->session->userdata('company_logo') ?>" style="max-height: 46px; max-width: 180px;" alt=""></a>
      <?php } ?>
      
      <!-- <div class="visible-xs-block"  >
         <p class="navbar-text newdash"><span class="label page-name"><?= $page_name; ?></span></p>        
      </div>  -->
      <!-- <div class="visible-xs-block"  >
         <p class="navbar-text newdash"><a href="mailto:<?=$this->session->userdata['compny_details']->company_email?>"><span class="label page-name">Contact Us</span></a></p>        
      </div>  -->
     
      
    
  
      <ul class="nav navbar-nav visible-xs-block">       
        <!-- <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li> -->
        <!-- <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li> -->
        <li><a class="sidebar-mobile-main-toggle" data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-paragraph-justify3"></i></a></li>
        <!-- <ul class="navbar-nav mr-auto">
        <li class="sidebar-mobile-main-toggle">
          <a class="navbar-text newdash"><a href="mailto:<?=$this->session->userdata['compny_details']->company_email?>"><span class="label page-name">Contact Us</span></a>
        </li>
        <li>
          <a class="navbar-text newdash"><a href="mailto:<?=$this->session->userdata['compny_details']->company_email?>"><span class="label page-name">Contact Us</span></a>
        </li>
        </ul> -->
      </ul>
    </div>


    <div class="navbar-collapse collapse" id="navbar-mobile">
      <ul class="nav navbar-nav icon-dash" style="float: inline-start">
        <li class="navbar-brand" ><span class="label page-name"  ><?= $page_name; ?></span></li>
        <!-- <li><a class="sidebar-control sidebar-main-toggle visible-xs" id="opentab"><i class="icon-paragraph-justify3"></i></a></li> -->
        <li class="nav-item visible-xs-block">
          <a href="mailto:<?=$this->session->userdata['compny_details']->company_email?>"><span class="  contact-us">Contact Us</span></a>
        </li>
        
      </ul>
      <div class="navbar-right ">
        <ul>
          <p class="navbar-text newdash btn-hide user-head" style="padding: 18px 10px;" ><a href="mailto:<?=$this->session->userdata['compny_details']->company_email?>"><span class="usertext" style="text-decoration: underline; color: #01669a; " >Contact Us</span></a></p>
          <!-- <p class="navbar-text btn-head"><span class="label btn-primary "><a class=
                      "fas fa-plus" href="#"  >Add route</a></span>
                      </p> -->
          <?php 
            $customerdata =  $this->CustomerModel->getOneCustomer(array('user_id' =>$this->session->userdata['user_id']));
            // die(print_r($customerdata));
            // die(print_r($this->session->userdata['compny_details']->company_email));
            // $customerdata->user_pic = ($customerdata->user_pic_resized != '') ? $customerdata->user_pic_resized : $customerdata->user_pic;
            $first_name = $_SESSION['first_name'];
            $last_name = $_SESSION['last_name'];
            $customer_id = $_SESSION['customer_id'];
            ?>
          <ul id="open-bx" class="nav navbar-nav " >
            <li class="dropdown dropdown-user user-head">
              <a class="dropdown-toggle" data-toggle="dropdown">
                <!-- <img src="<?php echo CLOUDFRONT_URL.'uploads/profile_image/'.$customerdata->user_pic ?>" alt=""> -->
                <!-- <span class="usertext"  style=""><?= $customerdata->first_name.' '.$customerdata->last_name ?></span> -->
                <span class="usertext" ><?= $first_name.' '.$last_name ."&nbsp;#". $customer_id?></span>
                <i class="caret"></i>
              </a>

              <ul class="dropdown-menu dropdown-menu-right" >
                <li><a href="<?= base_url('customers/updateAccount/').$_SESSION['customer_id'] ?>" >Profile Info</a></li>
                <li><a href="<?= base_url('customers/Logout') ?>" >Logout </a></li>
              </ul>
            </li>
          </ul>
        </ul>
      </div>
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
