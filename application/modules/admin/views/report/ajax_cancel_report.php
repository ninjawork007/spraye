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
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Lost Revenue</th>
                            <th>Sales Rep.</th>
                            <th>New Existing Customer</th>
                            <th>Cancel Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($AllCancelledProperty as $CanclPrprty){
                        ?>
                        <tr>
                            <td><?= date("d F, Y", strtotime($CanclPrprty->property_cancelled))?></td>
                            <td><?= $CanclPrprty->user_first_name ?> <?= $CanclPrprty->user_last_name ?></td>
                            <td><?= $CanclPrprty->first_name ?> <?= $CanclPrprty->last_name ?></td>
                            <td><?= date("d F, Y", strtotime($CanclPrprty->property_created))?></td>
                            <td><?= $CanclPrprty->property_title ?></td>
                            <td><?= $CanclPrprty->email ?></td>
                            <td><?= $CanclPrprty->work_phone ?></td>
                            <td></td>
                            <td></td>
                            <td><?= $CanclPrprty->tags == 1 ? "New" : "Existing" ?></td>
                            <td><?= $CanclPrprty->cancel_reason ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>