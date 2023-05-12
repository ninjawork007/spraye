<div class="panel panel-flat">
    <div class="panel-body">
        <div class="post-list" id="cancel-report-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
            <div class="table-responsive table-spraye" style="min-height: 0px">
                <table class="table" style="border:1px solid #6eb1fd;">
                    <thead>
                        <tr>
                            <th>Total Cancelled Properties</th>
                            <th>Total Cancelled Services</th>
                            <th>Total Cancelled Revenue</th>
                            <th>New Customer Cancelled Properties</th>
                            <th>New Customer Cancelled Services</th>
                            <th>New Customer Revenue Lost</th>
                            <th>Total Sales</th>
                            <th>Total Sales Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($report_details)){?>
                        <tr>
                            <td><?= $report_details['total_cancelled_properties'] ?></td>
                            <td><?= $report_details['total_cancelled_services'] ?></td>
                            <td>$<?= $report_details['total_cancelled_revenue'] ?></td>
                            <td><?= $report_details['lost_total_new_cancelled_props'] ?></td>
                            <td><?= $report_details['lost_total_new_cancelled_servs'] ?></td>
                            <td>$<?= $report_details['total_new_revenue_lost'] ?></td>
                            <td><?= $report_details['total_sales'] ?></td>
                            <td>$<?= $report_details['total_sales_revenue'] ?></td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center">No records found</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="panel panel-flat">
    <div class="panel-body">
        <div class="post-list" id="cancel-report-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
            <div class="table-responsive table-spraye" style="min-height: 0px">
                <table class="table" style="border:1px solid #6eb1fd;">
                    <thead>
                        <tr>
                            <th>Canceled Date</th>
                            <th>Canceled By</th>
                            <th>Customer Name</th>
                            <th>Customer Start Date</th>
                            <th>Property name</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Lost Revenue</th>
                            <th>Sales Rep.</th>
                            <th>New Existing Customer</th>
                            <th>Service</th>
                            <th>Program</th>
                            <th>Cancel Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $TotalRevnueLost = 0;
                        foreach($AllCancelledProperty as $CanclPrprty){
                            $TotalRevnueLost += $CanclPrprty->job_cost;
                        ?>
                        <tr>
                            <td><?= date("d F, Y", strtotime($CanclPrprty->property_cancelled))?></td>
                            <td><?= $CanclPrprty->user_first_name ?> <?= $CanclPrprty->user_last_name ?></td>
                            <td><a href="<?php echo base_url() ?>/admin/editCustomer/<?= $CanclPrprty->customer_id ?>" target="_blank"><?= $CanclPrprty->first_name ?> <?= $CanclPrprty->last_name ?></a></td>
                                <td><?= date("d F, Y", strtotime($CanclPrprty->start_date))?></td>
                            <td><?= $CanclPrprty->property_title ?></td>
                            <td><?= $CanclPrprty->property_address ?></td>
                            <td><?= $CanclPrprty->email ?></td>
                            <td><?= $CanclPrprty->work_phone ?></td>
                            <td>$<?= $CanclPrprty->job_cost ?></td>
                            <td><?= $CanclPrprty->SalesRep ?></td>
                            <td>
                                <?php
                                if($CanclPrprty->start_date >= date("Y-m-d 00:00:00", strtotime("-1 year"))){
                                    echo "New";
                                }else{
                                    echo "Existing";
                                }
                                ?>
                            </td>
                            <td><?= $CanclPrprty->service_cancelled ?></td>
                            <td><?= $CanclPrprty->program_cancelled ?></td>
                            <td><?= $CanclPrprty->cancel_reason ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" align="right"><b>Total</b></td>
                            <td>$ <?php echo $TotalRevnueLost ?></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>