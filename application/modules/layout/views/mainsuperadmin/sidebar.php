<style>
.fix-ul{
bottom: 0;
position: fixed;
z-index: 3000;
left: 10px;
}
</style>


<div class="sidebar sidebar-main">
    <div class="sidebar-content">
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    
                <!--<li class="<?= $active_sidebar == "dashboardnav" ? "active" : "" ?>"><a href="<?php echo base_url('admin'); ?>"><i class="icon-home4"></i> <span>Dashboard</span></a></li>-->

                <li class="<?= $active_sidebar == "AllCompany" ? "active" : "" ?>"><a href="<?php echo base_url('superadmin'); ?>"><i class="icon-equalizer"></i> <span>Summary</span></a></li>

                <li class="<?= $active_sidebar == "Customers" ? "active" : "" ?>"><a href="#"><i class="icon-users2"></i> <span>Customers</span></a></li>

               
               <!-- <li>
               <a href="#" > <img src="<?php echo base_url('assets/superadmin/image/super_admin.jpg') ?>" class="rounded-circle mr-2" alt="" height="34" style="border-radius:50%;"> <span>Super Admin</span></a>
               <ul>
               <li><a href="<?= base_url('superadmin/Logout') ?>" >Logout </a></li>
               <li><a href="<?= base_url('superadmin/setting') ?>" >Settings </a></li>
               <li><a href="" >Profile</a></li>
           </ul>
       </li> -->
        </ul>
            </div>
        </div>
        <!-- /main navigation -->
    </div>
</div>
