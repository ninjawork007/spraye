<style type="text/css">
  .toolbar {
    float: left;
    padding-left: 5px;
}

.form-control[readonly] {
  background-color: #ededed;
}
</style>

<div class="content">
    <div class="panel panel-flat">
       <!--  <div class="panel-heading">
            <h5 class="panel-title">Users list</h5>
           
        </div> -->
         
   <!--      <div class="panel panel-flat">
             <div class="panel-heading">
                   <h5 class="panel-title">
                        <div class="form-group">
                    
                        </div>
                   </h5>
              </div>
        </div> -->
        <div class="panel-body">
             <b><?php if($this->session->flashdata()): echo $this->session->flashdata('message'); endif; ?></b>
        
           <div class="panel panel-body" style="background-color:#ededed;" >
           <form id="serchform" action="<?= base_url('admin/reports/downloadCsv') ?>" method="post">            
              <div class="row">
                  <div class="col-md-3 multi-select-full">
                      <label>Users</label>
                      <select class="multiselect-select-all-filtering form-control" name="technician_name[]" id="technician_name" multiple="multiple">
                          <?php foreach ($users as $user): ?>
                              <option value="<?= $user->user_id ?>"> <?= $user->user_first_name ?> <?= $user->user_last_name ?></option>
                          <?php endforeach ?>
                      </select>
                  </div>
<!--                  <div class="col-md-2">-->
<!--                      <div class="form-group">-->
<!--                         <label>User Name</label>-->
<!--                          <input type="text" id="technician_name" name="technician_name" class="form-control" placeholder="Enter User Name">-->
<!--                      </div>-->
<!--                  </div>-->

                  <div class="col-md-2">
                      <div class="form-group">
                         <label>Customer Name</label>
                          <input type="text" id="customer_name" name="customer_name" class="form-control" placeholder="Enter Customer Name">
                      </div>
                  </div>
 
                 <div class="col-md-2">
                      <div class="form-group">
                         <label>Product Name</label>
                          <input type="text" id="product_name" name="product_name" class="form-control" placeholder="Enter Product Name">
                      </div>
                  </div>


                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Start Date</label>
                      <input type="date" id="job_completed_date_to" name="job_completed_date_to" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                    </div>
                  </div>

                  <div class="col-md-3">
                    <div class="form-group">
                      <label>End Date</label>
                      <input type="date" id="job_completed_date_from" name="job_completed_date_from" class="form-control pickaalldate" placeholder="YYYY-MM-DD">
                    </div>
                  </div>
              </div>
           
            <div class="text-center">
                <button type="button" class="btn btn-success" onClick="searchFilter()" ><i class="icon-search4 position-left"></i> Search</button>
                <button type="button" class="btn btn-primary" onClick="resetform()" ><i class="icon-reset position-left"></i> Reset</button>
                <button type="submit" class="btn btn-info"  ><i class="icon-file-download position-left"></i> CSV Download</button>

            </div>
            
          </form>
             
           </div>




 <div class="loading" style="display: none;">
    <center>
          <img style="padding-top: 30px;width: 7%;" src="<?php echo base_url().'assets/loader.gif'; ?>"/>
    </center>
   
 </div>

        <div class="post-list" id="postList">   

           <div  class="table-responsive table-spraye">
             <table  class="table datatable-colvis-state">    
                  <thead>  
                      <tr>
                          
                          
                          <th>User</th>
                          <th>Applicator Number</th>
						  
                          <th>Customer</th>
						  
						  <th>Amount</th>	
                         
                          <th>Property Name</th>
                          <th>City</th>
                          <th>Sq. Ft.</th>
                          <th>State</th>
                          <th>Zip</th>

                          <th>Property Address</th>
                         
                          <th>Product</th>
                          <th>Service Name</th>

                          <th>EPA #</th>
                         
                          <th>Application Rate</th>
                          <th>Estimate of Chemical Used</th>
                          <th>Amount of Mixture Applied</th>
                          <th>Weed/Pest Prevented</th>
                          
                          <th>Active Ingredients</th>
                          <th>Application Type</th>
                          <th>Re-Entry Time</th>
                          <th>Chemical Type</th>
                          <th>Restricted Product</th>
                          <th>Application Method</th>
                          <th>Area of Property Treated</th>

                          <th>Wind Speed</th>
                          <th>Wind Direction</th>
                          <th>Temperature</th>
                          <th>Date</th>
                          <th>Time Started</th>
                          <th>Time Completed</th>

                      </tr>  
                  </thead>
                  <tbody>


                  <?php if (!empty($report_details)) { foreach ($report_details as $value) { ?>

                      <tr>
                          
                          
                          <td style="text-transform: capitalize;"><?= $value->user_first_name.' '.$value->user_last_name ?></td>
                          <td><?= $value->applicator_number ?></td>
                          <td style="text-transform: capitalize;"><a href="<?= base_url('admin/editCustomer/').$value->customer_id ?>"><?= $value->first_name.' '.$value->last_name  ?> </a></td>
                          <td><?= $value->cost ?></td>
                          <td><?= $value->property_title ?></td>
                          <td><?= $value->property_city ?></td>
                          <td><?= $value->yard_square_feet ?></td>
                          <td><?= $value->property_state ?></td>
                          <td><?= $value->property_zip ?></td>
                          
                          <td><?= $value->property_address  ?></td>

                          <?php 

                             $product_details = reportProductDetails($value->thereportid);
                           
                           ?>
 
                          <td>
                              <?php

                                if ($product_details) {
                                    $product_name =   array_column($product_details, 'product_name');
                                    $numItems = count($product_name);
                                    $i = 0;
                                    foreach ($product_name as $key2 => $value2) {
                                        if(++$i === $numItems) {
                                           echo  '<span>'.$value2.'</span><br>';

                                          } else {
      
                                           echo  '<span>'.$value2.',</span><br>';

                                          }
                                    }
									
                                }
								else
										echo 'No report data';
                             ?>                            
                          </td>
                          <td><?= $value->job_name ?></td>
                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $epa_reg_nunber =   array_column($product_details, 'epa_reg_nunber');
                                    $numItems = count($epa_reg_nunber);
                                    $i = 0;

                                    foreach ($epa_reg_nunber as $key2 => $value2) {
                                       if(++$i === $numItems) {
                                           echo  '<span>'.(isset($value2) && $value2 !== '')?$value2:'None'.'</span><br>';
                                       } else {
                                           echo  '<span>'.(isset($value2) && $value2 !== '')?$value2:'None'.',</span><br>';
                                       }

                                    }
                                }
                                else
                                    echo 'No report data';
                             ?>
                          </td>
                          
                          <td>
                          <?php
                                 
                                if ($product_details) {
                                    $application_rate =   array_column($product_details, 'application_rate');
                                    $numItems = count($application_rate);
                                    $i = 0;
                                    foreach ($application_rate as $key2 => $value2) {
                                         if(++$i === $numItems) { 
                                           echo  '<span>'.$value2.'</span><br>';

                                         } else {
                                            echo  '<span>'.$value2.',</span><br>';

                                         } 
                                    }
                                }else
                                    echo 'No report data';
                             ?>                      
                          </td>

                           <td>

                           <?php
                                 
                                if ($product_details) {
                                    $estimate_of_pesticide_used =   array_column($product_details, 'estimate_of_pesticide_used');
                                    $numItems = count($estimate_of_pesticide_used);
                                    $i = 0;
                                    foreach ($estimate_of_pesticide_used as $key2 => $value2) {
                                      if(++$i === $numItems) { 
                                        echo  '<span>'.$value2.'</span><br>';
                                      } else {
                                        echo  '<span>'.$value2.',</span><br>';
                                      } 

                                    }
                                }else
                                    echo 'No report data';
                             ?>
                            
                          </td> 

                          
                          <td>
                          <?php
                                 
                                if ($product_details) {
                                    $amount_of_mixture_applied =   array_column($product_details, 'amount_of_mixture_applied');
                                    $numItems = count($amount_of_mixture_applied);
                                    $i = 0;
                                    foreach ($amount_of_mixture_applied as $key2 => $value2) {
                                        if ((strlen($value2) !== 0)) {
                                            if (++$i === $numItems) {
                                                echo '<span>' . $value2 . '</span><br>';

                                            } else {
                                                echo '<span>' . $value2 . ',</span><br>';

                                            }
                                        } else {
                                            if(++$i === $numItems) {
                                                echo  '<span>None</span><br>';
                                            } else {
                                                echo  '<span>None, </span><br>';
                                            }
                                        }
                                    }
                                }else
                                    echo 'No report data';
                             ?>                      
                          </td>

                           <td>
                            <?php
                                 
                                if ($product_details) {
                                    $weed_pest_prevented =   array_column($product_details, 'weed_pest_prevented');
                                    $numItems = count($weed_pest_prevented);
                                    $i = 0;

                                    foreach ($weed_pest_prevented as $key2 => $value2) {
                                       if ((strlen($value2) !== 0)){
                                           if(++$i === $numItems) {
                                               echo  '<span>'.$value2.'</span><br>';
                                           } else {
                                               echo  '<span>'.$value2.', </span><br>';
                                           }
                                       } else {
                                           if(++$i === $numItems) {
                                               echo  '<span>None</span><br>';
                                           } else {
                                               echo  '<span>None, </span><br>';
                                           }
                                       }
                                    }
                                }else
                                    echo 'No report data';
                             ?>
                          </td>


                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $active_ingredients =   array_column($product_details, 'active_ingredients');
                                    $numItems = count($active_ingredients);
                                    $i = 0;
                                    foreach ($active_ingredients as $key2 => $value2) {
                                        if ((strlen($value2) !== 0)) {
                                            if(++$i === $numItems) {
                                                echo  '<span>'.$value2.'</span><br>';
                                            } else {
                                                echo  '<span>'.$value2.',</span><br>';
                                            }
                                        } else {
                                            if(++$i === $numItems) {
                                                echo  '<span>None</span><br>';
                                            } else {
                                                echo  '<span>None,</span><br>';
                                            }
                                        }

                                    }
                                }else
                                    echo 'No report data';
                             ?>
                          </td>

                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $application_type =   array_column($product_details, 'application_type');
                                    $numItems = count($application_type);
                                    $i = 0;
                                    foreach ($application_type as $key2 => $value2) {

                                        if ($value2==1) {
                                              $app_type = 'Broadcast';
                                          } else if($value2==2) {
                                              $app_type = 'Spot Spray';
                                          } else if ($value2==3) {
                                              $app_type = 'Granular';
                                          } else if (strlen($value2) == 0) {
                                            $app_type = 'None';
                                        } else{
                                                  $app_type = $value2;
                                        }

                                      if(++$i === $numItems) {
                                        echo  '<span>'.$app_type.'</span><br>';
                                      } else {
                                        echo  '<span>'.$app_type.',</span><br>';
                                      }

                                    }
                                }else
                                    echo 'No report data';
                             ?>
                          </td>

                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $re_entry_time =   array_column($product_details, 're_entry_time');
                                    $numItems = count($re_entry_time);
                                    $i = 0;
                                    foreach ($re_entry_time as $key2 => $value2) {
                                        if ((strlen($value2) !== 0)) {
                                            if(++$i === $numItems) {
                                                echo  '<span>'.$value2.'</span><br>';
                                            } else {
                                                echo  '<span>'.$value2.',</span><br>';
                                            }
                                        } else {
                                            if(++$i === $numItems) {
                                                echo  '<span>None</span><br>';
                                            } else {
                                                echo  '<span>None,</span><br>';
                                            }
                                        }


                                    }
                                }else
                                    echo 'No report data';
                             ?>
                          </td>

                          <td>
                            <?php
                                 
                                if ($product_details) {
                                    $chemical_type =   array_column($product_details, 'chemical_type');
                                    $numItems = count($chemical_type);
                                    $i = 0;
                                    foreach ($chemical_type as $key2 => $value2) {
                                        if ((strlen($value2) !== 0)) {
                                            if(++$i === $numItems) {
                                                echo  '<span>'.$value2.'</span><br>';
                                            } else {
                                                echo  '<span>'.$value2.',</span><br>';
                                            }
                                        } else {
                                            if(++$i === $numItems) {
                                                echo  '<span>None</span><br>';
                                            } else {
                                                echo  '<span>None,</span><br>';
                                            }
                                        }


                                    }
                                }else
                                    echo 'No report data';
                             ?>
                          </td>

                            <td>
                                <?php
                                    if ($product_details) {
                                        $restricted_product =   array_column($product_details, 'restricted_product');
                                        $numItems = count($restricted_product);
                                        $i = 0;
                                        foreach ($restricted_product as $key2 => $value2) {
                                            if ((strlen($value2) !== 0)) {
                                                if(++$i === $numItems) {
                                                    echo  '<span>'.$value2.'</span><br>';
                                                } else {
                                                    echo  '<span>'.$value2.',</span><br>';
                                                }
                                            } else {
                                                if(++$i === $numItems) {
                                                    echo  '<span>None</span><br>';
                                                } else {
                                                    echo  '<span>None,</span><br>';
                                                }
                                            }
                                        }
                                    }else
                                        echo 'No report data';
                                ?>
                            </td>

                            <td>
                                <?php
                                    if ($product_details) {
                                        $application_method =   array_column($product_details, 'application_method');
                                        $numItems = count($application_method);
                                        $i = 0;
                                        foreach ($application_method as $key2 => $value2) {
                                             if ($value2==1) {
                                               $application_method_name = 'Ride On';
                                             } else if($value2==2) {
                                               $application_method_name = 'Skid Spray';
                                             } else if ($value2==3) {
                                               $application_method_name = 'Backback';
                                             } else if ($value2==4) {
                                               $application_method_name = 'Walk Behind Spreader';
                                             } else if (strlen($value2) == 0) {
                                                    $application_method_name = 'None';
                                             } else {
                                                 $application_method_name = $value2;
                                             }

                                            if (++$i === $numItems) {
                                                echo  '<span>' . $application_method_name . '</span><br>';
                                            } else {
                                                echo  '<span>' . $application_method_name . ',</span><br>';
                                            }
                                        }
                                    }else
                                        echo 'No report data';
                                ?>
                            </td>

                            <td>
                                <?php
                                    if ($product_details) {
                                        $area_of_property_treated =   array_column($product_details, 'area_of_property_treated');
                                        $numItems = count($area_of_property_treated);
                                        $i = 0;
                                        foreach ($area_of_property_treated as $key2 => $value2) {
                                            if ((strlen($value2) !== 0)) {
                                                if(++$i === $numItems) {
                                                    echo  '<span>'.$value2.'</span><br>';
                                                } else {
                                                    echo  '<span>'.$value2.',</span><br>';
                                                }
                                            } else {
                                                if(++$i === $numItems) {
                                                    echo  '<span>None</span><br>';
                                                } else {
                                                    echo  '<span>None,</span><br>';
                                                }
                                            }
                                        }
                                    }else
                                        echo 'No report data';
                                ?>
                            </td>


                          
                          <td><?= round($value->wind_speed,2) .' MPH'  ?></td>
                          <td><?= $value->direction  ?></td>
                          <td><?= $value->temp.' &#8457;'  ?></td>
                          <td><?= date('m-d-Y', strtotime($value->job_completed_date))  ?></td>
                          <td><?= $value->job_start_time  ?></td>
                          <td><?= $value->job_completed_time  ?></td>
                      </tr>
                  
                  <?php  } } else { ?> 

                    <tr>
                        <td>No record found</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                  <?php }  ?>

                  </tbody>
              </table>
           </div>
         </div> 


        </div>
        
        
    </div>
</div>


<script>



function resetform(){

  $('#serchform')[0].reset();
  searchFilter();
}


function searchFilter() {

    var customer_name = $('#customer_name').val();
    var technician_name = $('#technician_name').val();
    var product_name = $('#product_name').val();
    var job_completed_date_to = $('#job_completed_date_to').val();
    var job_completed_date_from = $('#job_completed_date_from').val();
    $('.loading').css("display", "block");
   $('#postList').html('');
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/ajaxPaginationData/',
        data:'customer_name='+customer_name+'&technician_name='+technician_name+'&product_name='+product_name+'&job_completed_date_to='+job_completed_date_to+'&job_completed_date_from='+job_completed_date_from,
        
        success: function (html) {
            $(".loading").css("display", "none");
            $('#postList').html(html);
            tableintal();
          
        }
    });
}



   $(document).ready(function() {
      tableintal();

   })

   function tableintal(argument) {
      $('.datatable-colvis-state').DataTable({
          "iDisplayLength": <?= $this ->session->userdata('compny_details')-> default_display_length?>,
          "aLengthMenu": [[10,20,50,100,200,500],[10,20,50,100,200,500]],
        buttons: [
            {
                extend: 'colvis',
                text: '<i class="icon-grid3"></i> <span class="caret"></span>',
                className: 'btn bg-indigo-400 btn-icon'
            }
        ],
        stateSave: true,
        columnDefs: [
            {
                targets: -1,
                visible: false
            }
        ],

          
    });
   }


function csvfile() {
    var customer_name = $('#customer_name').val();
    var technician_name = $('#technician_name').val();
    var job_completed_date_to = $('#job_completed_date_to').val();
    var job_completed_date_from = $('#job_completed_date_from').val();
    $.ajax({
        type: 'POST',
        url: '<?php echo base_url(); ?>admin/reports/downloadCsv/',
        data:'customer_name='+customer_name+'&technician_name='+technician_name+'&job_completed_date_to='+job_completed_date_to+'&job_completed_date_from='+job_completed_date_from,

        success: function (response) {
      //    alert(response);
        }
    });
}


</script>
