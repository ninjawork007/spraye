<div class="table-responsive table-spraye">
    <table class="table" style="border:1px solid #6eb1fd;">
        <thead>
            <tr>
                <th>Total Cancelled Properties</th>
                <th>Total Cancelled Services</th>
                <th>Total Cancelled Revenue</th>
                <th>New Customer Cancels</th>
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