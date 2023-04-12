<style>
   .fix-ul{
   bottom: 0;
   position: fixed;
   z-index: 3000;
   left: 10px;
   }
   .btndiv {
   float: left;
   padding: 3px;
   }
</style>
<div class="sidebar sidebar-main">
   <div class="sidebar-content">
      <!-- Main navigation -->
      <div class="sidebar-category sidebar-category-visible">
         <div class="category-content no-padding">
            <ul class="navigation navigation-main navigation-accordion">
               <li class="<?= $active_sidebar == "dashboardnav" ? "active" : "" ?>"><a href="<?php echo base_url('admin'); ?>"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
               <li class="<?= $active_sidebar == "customer" ? "active" : "" ?>"><a href="<?= base_url('admin/customerList') ?>"><i class="icon-users"></i> <span>Customers</span></a></li>
               <li class="<?= $active_sidebar == "properties" ? "active" : "" ?>"><a href="<?= base_url('admin/propertyList') ?>"><i class="icon-office"></i> <span>Properties</span></a></li>
               
				<li>
               <a href="#"><i class="icon-cust fa fa-calendar-o fa-lg" aria-hidden="true"></i> <span>Programs</span></a>
               <ul>
                  <li class="<?= $active_sidebar == "program" ? "active" : "" ?>"><a href="<?= base_url('admin/programList') ?>"> <span>Programs</span></a></li>
                  
                  <li class="<?= $active_sidebar == "programArchive" ? "active" : "" ?>"><a href="<?= base_url('admin/programListArchived') ?>"> <span>Archived Programs</span></a></li>
               </ul>
            </li>
				
				
				
				
				
               <li class="<?= $active_sidebar == "product" ? "active" : "" ?>"><a href="<?= base_url('admin/productList') ?>"><i class="icon-grid3"></i> <span>Products</span></a></li>
 
               <li class="<?= $active_sidebar == "jobNav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/Job');?>"><i class="icon-cust fa fa-briefcase fa-lg" aria-hidden="true"></i> <span>Services</span></a></li>
               <li class="<?= $active_sidebar == "mangeUserNav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/users');?>"><i class="icon-users4"></i> <span>Manage Users</span></a></li>

            <li>
               <a href="#"><i class="icon-cust fa fa-map-marker fa-lg"></i> <span>Routing</span></a>
               <ul>
                  <li class="<?= $active_sidebar == "available_routes" ? "active" : "" ?>" ><a href="<?= base_url('admin/availableRoutes') ?>" >Scheduled Routes</a></li>

                  <li class="<?= $active_sidebar == "unass_serv_routing" ? "active" : "" ?>" ><a href="<?= base_url('admin/assignJobs') ?>" >Unassigned Services</a></li>
                  
                  <li class="<?= $active_sidebar == "arch_serv_routing" ? "active" : "" ?>" ><a href="<?= base_url('admin/assignJobsArchived') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Archived Services</a></li>
               </ul>
            </li>

               <!-- <li class="<?= $active_sidebar == "invoicenav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/Invoices');?>"><i class="icon-file-text2"></i> <span>Invoices</span></a></li> -->

               <li>
                  <a href="#"><i class="icon-file-text2"></i> <span>Invoices</span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "invoicenav" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/Invoices');?>" >Active Invoices</a></li>
                    
                    <li class="<?= $active_sidebar == "ainvoicenav" ? "active" : "" ?>" ><a href="<?= base_url('admin/Invoices/archived') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Archived Invoices</a></li>
                  </ul>
                </li>

               <!-- <li class="<?= $active_sidebar == "ainvoicenav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/Invoices/archived');?>"><i class="icon-archive"></i> <span>Archived Invoices</span></a></li> -->

                 <li class="<?= $active_sidebar == "estimatenav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/Estimates');?>"><i class="icon-pencil"></i> <span>Estimates <span class="label bg-success">new</span></span></a></li>
               
               
                
                <li>
                    <a href="#"><i class="icon-stack2"></i> <span>Reports</span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "reports" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports');?>" >Completed Service Log</a></li>
                    <li class="<?= $active_sidebar == "invoiceAgeReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/invoiceAgeReport');?>" >Invoice Age Report</a></li>
                    <li class="<?= $active_sidebar == "salesTaxReport" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesTaxReport') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Sales Tax Report</a></li>
                  </ul>
                </li>
                <li>
                    <a href="#"><i class="icon-stack2"></i> <span>Inventory</span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "dashboard" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Dashboard/');?>" >Dashboard</a></li>
                    <li class="<?= $active_sidebar == "items" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Items/');?>" >Items</a></li>
                     <li> 
                        <a href="#"><i class="icon-stack2"></i> <span>Procurement</span></a>
                        <ul>
                           <li class="<?= $active_sidebar == "purchases" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Purchases');?>" >Purchases</a></li>
                           <li class="<?= $active_sidebar == "returns" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Purchases/returns');?>" >Returns</a></li>
                        </ul>
                     </li>
                    <li class="<?= $active_sidebar == "quantityAdjustments" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Quantity Adjustments</a></li>
                    <li class="<?= $active_sidebar == "tranfers" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Transfers</a></li>
                    <li class="<?= $active_sidebar == "vendors" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Vendors</a></li>
                    <li class="<?= $active_sidebar == "itemsTypes" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Item Types</a></li>
                    <li class="<?= $active_sidebar == "brands" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Brands</a></li>
                    <li class="<?= $active_sidebar == "locations" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Locations</a></li>
                    <li class="<?= $active_sidebar == "alerts" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Alerts </a></li>
                  </ul>
                </li>

 


            </ul>
            <!--       <ul class="navigation navigation-main navigation-accordion fix-ul">
               <li class=""><a href="<?= base_url("admin/logout")?>"><img src="<?php echo base_url('assets/admin/image/4.jpg')?>" class="rounded-circle mr-2" alt="" height="34" style="border-radius:50%;"><span> Logout</span></a></li> 
                 
               <li>
                 <?php 
                  $admindata =  $this->Administrator->getOneAdmin(array('user_id' =>$this->session->userdata['user_id']));
                  
                  ?>
                 <a href="#" > <img src="<?php echo CLOUDFRONT_URL.'uploads/profile_image/'.$admindata->user_pic ?>" class="rounded-circle mr-2" alt="" height="34" style="border-radius:50%;"> <span><?= $this->session->userdata['user_first_name'].' '.$this->session->userdata['user_last_name'] ?></span>  </a>
                 <ul>
                    <li><a href="<?= base_url('admin/Logout') ?>" >Logout </a></li>
                    <li><a href="<?= base_url('technician/dashboard/') ?>" >Switch to Technician View </a></li>
                    <li><a href="<?= base_url('admin/setting') ?>" >Settings </a></li>
                    <li><a href="<?=  base_url('admin/users/updateProfile') ?>" >Profile</a></li>
                 </ul>
               </li>
               </ul> -->
            <a class="navbar-brand sidebar-control sidebar-main-toggle hidden-xs" href="#" onclick="Togglefunction()">
            <i class="fa fa-angle-left icon-close" aria-hidden="true"></i></a>
         </div>
      </div>
      <!-- /main navigation -->
   </div>
</div>
<script type="text/javascript">
   function Togglefunction()
   {
    $('.navbar-brand.sidebar-control.sidebar-main-toggle.hidden-xs > .icon-close ,.img-open').hide(); 
    $('.icon-open').show();
   }
     function Borderfunction()
   {
    $('.navbar-brand.sidebar-control.sidebar-main-toggle.hidden-xs > .icon-close ,.img-open').hide(); 
    $('.icon-open').show();
   }
   
    $('.icon-close').click(function(e){
    $('#nav-br').addClass('br-btm-full');
    $('#nav-br').removeClass('br-btm');
   
   })
</script>
