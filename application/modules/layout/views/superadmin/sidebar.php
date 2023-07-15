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
               <li class="<?= $active_sidebar == "properties" ? "active" : "" ?>"><a href="<?= base_url('admin/propertyList') ?>"><i class="icon-office"></i> <span>Properties</span></a>
				   <ul>
					  <li class="<?= $active_sidebar == "properties" ? "active" : "" ?>"><a href="<?= base_url('admin/propertyList') ?>"> <span>Properties</span></a></li>
					  <li class="<?= $active_sidebar == "prospect" ? "active" : "" ?>"><a href="<?= base_url('admin/prospectProperty') ?>"> <span>Prospects</span></a></li>
				   </ul>
            	</li>
				<li>
                  <a href="#"><i class="icon icon-calendar" aria-hidden="true"></i> <span>Programs</span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "program" ? "active" : "" ?>"><a href="<?= base_url('admin/programList') ?>"> <span>Programs</span></a></li>

                    <li class="<?= $active_sidebar == "programArchive" ? "active" : "" ?>"><a href="<?= base_url('admin/programListArchived') ?>"> <span>Archived Programs</span></a></li>
                  </ul>
                </li>
               <li class="<?= $active_sidebar == "product" ? "active" : "" ?>"><a href="<?= base_url('admin/productList') ?>"><i class="icon-grid3"></i> <span>Products</span></a></li>

               <li class="<?= $active_sidebar == "jobNav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/Job');?>"><i class="icon icon-briefcase" aria-hidden="true"></i> <span>Services</span></a></li>
               <li class="<?= $active_sidebar == "mangeUserNav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/users');?>"><i class="icon-users4"></i> <span>Manage Users</span></a></li>

            <li>
               <a href="#"><i class="icon-cust fa fa-map-marker fa-lg"></i> <span>Scheduling</span></a>
               <ul>
                  <li class="<?= $active_sidebar == "available_routes" ? "active" : "" ?>" ><a href="<?= base_url('admin/availableRoutes') ?>" >Scheduled Routes</a></li>
                  <li class="<?= $active_sidebar == "available_services" ? "active" : "" ?>" ><a href="<?= base_url('admin/manageJobs') ?>" >Scheduled Services</a></li>

                  <?php $assign_job_default = ($this->session->userdata('assign_job_view')) ? $this->session->userdata('assign_job_view') : 0;
					  if($assign_job_default == 1){?>
                        <li class="<?= $active_sidebar == "unass_serv_routing" ? "active" : "" ?>" ><a href="<?= base_url('admin/assignJobsMap') ?>" >Unassigned Services</a></li>
                        <?php } else { ?> 
                            <li class="<?= $active_sidebar == "unass_serv_routing" ? "active" : "" ?>" ><a href="<?= base_url('admin/assignJobs') ?>" >Unassigned Services</a></li>
                            <?php } ?>
                  <li class="<?= $active_sidebar == "arch_serv_routing" ? "active" : "" ?>" ><a href="<?= base_url('admin/assignJobsArchived') ?>">Archived Services</a></li>
               </ul>
            </li>

               

               <li>
                  <a href="#"><i class="icon-file-text2"></i> <span>Invoices</span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "invoicenav" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/Invoices');?>" >Active Invoices</a></li>
                    <li class="<?= $active_sidebar == "ainvoicenav" ? "active" : "" ?>" ><a href="<?= base_url('admin/Invoices/archived') ?>">Archived Invoices</a></li>
                  </ul>
               </li>

               <li class="<?= $active_sidebar == "estimatenav" ? "active" : "" ?>"><a href="<?php echo base_url('admin/Estimates');?>"><i class="icon-pencil"></i> <span>Estimates</span></a></li>

               <li class="<?= $active_sidebar == "email_marketing" ? "active" : "" ?>"><a href="<?php echo base_url('admin/reports/emailMarketing');?>"><i class="icon-envelop3"></i> <span>Email<br>Marketing  <span class="label bg-primary">beta</span></span></a></li>

               <li>
                  <a href="#"><i class="icon-stack2"></i> <span>Reports</span></a>
                  <ul>
                     <li class="<?= $active_sidebar == "techAvailableWorkReport" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/techAvailableWorkReport') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Available Work Report</a></li>
                     <li class="<?= $active_sidebar == "creditReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/creditReport');?>" >Credit Report</a></li>
                    <li class="<?= $active_sidebar == "customerGrowthReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/customerGrowthReport');?>">Customer Growth Analysis Report</a></li> 
                    <li class="<?= $active_sidebar == "reports" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports');?>" >Completed Service Log</a></li>
                    <li class="<?= $active_sidebar == "invoiceAgeReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/invoiceAgeReport');?>" >Invoice Age Report</a></li>
                    <li class="<?= $active_sidebar == "marketingCustomerDataReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/marketingCustomerDataReport');?>">Marketing / Customer Data Report</a></li>
                    <li class="<?= $active_sidebar == "materialResourcePlanningReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/MaterialResourcePlanningReport');?>" >Material Resource Planning</a></li>
					      <li>
                     <li class="<?= $active_sidebar == "revenueServiceType" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/revenueServiceType');?>">Revenue by Service Type</a></li>
                     <li>
                     <a href="#"><span>Sales</span></a>
                     <ul>
                        <li class="<?= $active_sidebar == "cancelService" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/cancelService');?>" >Cancel Details Report</a></li>
                        <li class="<?= $active_sidebar == "cancelReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/cancelReport');?>">Canceled Properties Report</a></li>
                        <li class="<?= $active_sidebar == "skippedServicesReport" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/reports/skippedServicesReport');?>">Skipped Services Report</a></li>
                        <li class="<?= $active_sidebar == "pipelineSummary" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesPipelineSummary') ?>">Sales Pipeline Summary Report (real time)</a></li>
                        <li>
                           <a href="#"><span>Sales Summary</span></a>
                           <ul>
                              <li class="<?= $active_sidebar == "salesSummary" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesSummary  ') ?>"  >Sales Report</a></li>
                              <li class="<?= $active_sidebar == "serviceSummary" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/serviceSummary  ') ?>"  >Service Report</a></li>
                           </ul>
                        </li>
                        
                        <li class="<?= $active_sidebar == "pipelineDetail" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesPipelineDetail') ?>"  >Sales Pipeline Detail Report (real time)</a></li>
                        <li class="<?= $active_sidebar == "commissionReport" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesCommissionReport') ?>"  >Commission Report</a></li>
                        <li class="<?= $active_sidebar == "bonusReport" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesBonusReport') ?>"  >Bonus Report</a></li>
                     </ul>
                    </li>
                    <li class="<?= $active_sidebar == "salesTaxReport" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/salesTaxReport') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Sales Tax Report</a></li>
                    <li class="<?= $active_sidebar == "techEfficiencyReport" ? "active" : "" ?>" ><a href="<?= base_url('admin/reports/techEfficiencyReport') ?>"  class="<?= $active_sidebar == "reports" ? "active" : "" ?>" >Technician Efficiency Report</a></li>
                    
                  </ul>
                </li>

                 <li>
                    <a href="#"><i class="icon-truck"></i> <span>Fleet Management <span class="label bg-success">new</span></span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "allVehicles" ? "active" : "" ?>" ><a href="<?php echo base_url('admin/allVehicles');?>" >Fleet Vehicles</a></li>
                  </ul>
                </li>

                <li>
                    <a href="#"><i class="icon-stack2"></i> <span>Inventory <span class="label bg-primary">beta</span></span></a>
                  <ul>
                    <li class="<?= $active_sidebar == "dashboard" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Dashboard/');?>" >Dashboard</a></li>
                    <li> 
                        <a href="#"><i class="icon-stack2"></i> <span>Items</span></a>
                        <ul>
                           <li class="<?= $active_sidebar == "items" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Items');?>" >Overview</a></li>
                           <li class="<?= $active_sidebar == "overall_item_quantity" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Items/overall_item_quantity');?>" >Item Quantity</a></li>
                        </ul>
                     </li> 
                    <li> 
                        <a href="#"><i class="icon-stack2"></i> <span>Procurement</span></a>
                        <ul>
                           <li class="<?= $active_sidebar == "purchases" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Purchases');?>" >Purchases</a></li>
                           <li class="<?= $active_sidebar == "receiving" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Purchases/receiving');?>" >Receiving</a></li>
                           <li class="<?= $active_sidebar == "returns" ? "active" : "" ?>" ><a href="<?php echo base_url('inventory/Frontend/Purchases/returns');?>" >Returns</a></li>
                        </ul>
                     </li>
                    <li class="<?= $active_sidebar == "quantityAdjustments" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/Adjustments') ?>" >Quantity Adjustments</a></li>
                    <li class="<?= $active_sidebar == "tranfers" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/Transfers') ?>" >Transfers</a></li>
                    <li class="<?= $active_sidebar == "vendors" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/Vendors') ?>" >Vendors</a></li>
                    <li class="<?= $active_sidebar == "itemTypes" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/ItemTypes') ?>" >Item Types</a></li>
                    <li class="<?= $active_sidebar == "brands" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/Brands') ?>"  >Brands</a></li>
                    <li class="<?= $active_sidebar == "locations" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/Locations') ?>"  >Locations</a></li>
                    <li class="<?= $active_sidebar == "materialResourcePlanningReportINV" ? "active" : "" ?>" ><a href="<?= base_url('inventory/Frontend/Purchases/MaterialResourcePlanningReport') ?>"  >Material Resource Planning </a></li>
                  </ul>
                </li>

               <li class="<?= $active_sidebar == "all_notes" ? "active" : "" ?>">
                  <a href="<?php echo base_url('admin/notesViewAll');?>"><i class="icon-stack"></i> <span>Notes</span></a>
               </li>


            </ul>
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
