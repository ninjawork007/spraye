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
       <?php 
            switch ($page_name) {
              case 'Summary dashboard':
            
              echo '<p class="navbar-text btn-head"><span class="label btn-success"><a href="#" >Manage Customers</a></span>
                      </p>
                      <p class="navbar-text btn-head"><span class="label btn-primary "><a class="icon-plus3" href="'.base_url('superadmin/addCompany').'" >Add Customer</a></span>
                      </p>';

                
                break;

                case 'Manage Subscription':
            
              echo '<p class="navbar-text btn-head" ><span class="label btn-success"><a href="#"  data-toggle="modal" data-target="#modal_add_note" >MAKE A NOTE</a></span>
                      </p>
                      <p class="navbar-text btn-head"><span class="label btn-warning "><a class="" href="#" >PUT ON HOLD</a></span>
                      </p>
                       <p class="navbar-text btn-head"><span class="label btn-danger "><a class="" href="#" >TERMINATE</a></span>
                      </p>
                      <p class="navbar-text btn-head"><span class="label btn-info "><a class=""  data-toggle="modal" data-target="#modal_change_plan" >MANAGE SUBSCRIPTION</a></span>
                      </p>

                      ';

                
                break;
              }
              ?>
      <ul class="nav navbar-nav navbar-right">
       


       <li class="dropdown dropdown-user user-head">
          <a class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo base_url('assets/superadmin/image/super_admin.jpg') ?>" class="rounded-circle mr-2" alt="" height="34" style="border-radius:50%;"> <span>Super Admin</span>
            <i class="caret"></i>
          </a>

          <ul class="dropdown-menu dropdown-menu-right">
            <li><a href="" >Profile</a></li>
            <li><a href="<?= base_url('superadmin/setting') ?>" >Settings </a></li>
            <li><a href="<?= base_url('superadmin/Logout') ?>" >Logout </a></li>

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
