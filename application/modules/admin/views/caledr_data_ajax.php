

<?php 
   if (!empty($assign_data)) {
      $assign_data_count = count($assign_data);
       foreach ($assign_data as $key => $value) {
 ?>                                
<div class="col-lg-6 col-md-6 col-sm-12 col-12 scroll-dt">
   <ul class="list-group scroll-dts">
      <!-- <li class="list-group-item head-month">01st Thursday</li> -->
      <li class="list-group-item head-month"><?= date('jS F', strtotime($value->job_assign_date)); ?></li>
      <?php foreach ($value->assign_data_result as $key2 => $value2) { 
         ?>
      <li class="list-group-item li-cx li-data">
         <p class="userdata-txt"><a href="" class="get_assign_details cx-txt" id="<?= $value2->technician_job_assign_id ?>" > <?= $value2->customer_first_name.' '.$value2->customer_last_name ?> | <span class="Property-text"><?= wordwrap($value2->property_title,12,"<br>");  ?> </span></a>
         </p>
      </li>
      <?php } ?>
   </ul>
</div>

  <?php    if($assign_data_count==1) { ?>
       <div class="col-lg-6 col-md-6  col-sm-12 col-12  cal-data">
         <ul class="list-group">
            <!-- <li class="list-group-item head-month">01st Thursday</li> -->
            <li class="list-group-item head-month"><?=                                     date('jS F', strtotime("+1 day",strtotime($value->job_assign_date)  )); ?></li>
            <li class="list-group-item li-cx">
               No Data Found                                           
            </li>
         </ul>
      </div>
  <?php } ?>


<?php  } } else { ?> 
<div class="col-lg-6 col-md-6  col-sm-12 col-12  cal-data ">
   <ul class="list-group">
      <!-- <li class="list-group-item head-month">01st Thursday</li> -->
      <li class="list-group-item head-month"><?= date('jS F', strtotime($currentdate)); ?></li>
      <li class="list-group-item li-cx">
         No Data Found                                           
      </li>
   </ul>
</div>
<div class="col-lg-6 col-md-6  col-sm-12 col-12  cal-data ">
   <ul class="list-group">
      <!-- <li class="list-group-item head-month">01st Thursday</li> -->
      <li class="list-group-item head-month"><?=                                     date('jS F', strtotime("+1 day",strtotime($currentdate))); ?></li>
      <li class="list-group-item li-cx">
         No Data Found                                           
      </li>
   </ul>
</div>
<?php  } ?>

