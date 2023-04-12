<?php 

// echo "<pre>";
// print_r($result['data']);

  if ( isset($result['data']) && !empty($result['data']) ) {
    
     foreach ($result['data'] as $key => $value) {

      ?>
     

                      <tr>                        
                        <td class="text-center">
                          <?php 

                          switch ($value->status) {
                            case 'succeeded':
                             echo '<i class="icon-checkmark3"></i>';  
                              break;
                            
                            default:
                             echo '<i class="icon-cross2"></i>';
                              break;
                          }

                           ?>
                            

                        </td>
                        <td>
                          <a href="#" class="text-default display-inline-block">
                            <?= date("H:i, d F Y",$value->created) ?>                   
                          </a>
                        </td>
                       
                        <td>
                          <a href="#" class="text-default display-inline-block">
                           <?= $value->payment_method_details->card->network.'..'.$value->payment_method_details->card->last4 ?>                           
                          </a>
                        </td>

                        <td>
                          <a href="#" class="text-default display-inline-block">
                         $<?=  floatval($value->amount/100)  ?>                           
                          </a>
                        </td>

                        <td class="text-center">
                          <ul class="icons-list">
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-more2"></i></a>
                         
                            </li>
                          </ul>
                        </td>
                      </tr>

<?php }  ?>


     <tr>
     <td colspan="5" class="last_td" ><a href="" id="load_more" >LOAD MORE</a></td>               
                     
     </tr>


 <?php } else {  ?>

<tr><td colspan="5" class="last_td" >No DATA FOUND</td></tr>

<?php } ?>
                    