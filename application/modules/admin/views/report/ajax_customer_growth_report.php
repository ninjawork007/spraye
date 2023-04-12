<div class="table-responsive table-spraye">
    <table class="table" style="border:1px solid #6eb1fd;">
        <thead>
            <tr>
                <th>Date Range</th>
                <th>Total Starting Properties</th>
                <th>Total New Properties</th>
                <th>Total Cancels</th>
                <th>Cancel %</th>
                <th># of Cancels/Total # of new sales</th>
                <th>Total Ending Properties Growth Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($report_details)){
                if(!empty($comparison_details)){?>
                    <tr>
                        <td><?= $report_details['date_range'] ?></td>
                        <td><?= $report_details['total_starting_properties'] ?></td>
                        <td><?= $report_details['total_new_properties'] ?></td>
                        <td><?= $report_details['total_cancels'] ?></td>
                        <td><?= $report_details['total_cancelled_percent'] ?>%</td>
                        <td><?= $report_details['total_cancels_vs_sales'] ?></td>
                        <td><?= $report_details['total_ending_property_growth'] ?>%</td>
                    </tr>
                    <tr>
                        <td><?= $comparison_details['date_range'] ?></td>
                        <td><?= $comparison_details['total_starting_properties'] ?></td>
                        <td><?= $comparison_details['total_new_properties'] ?></td>
                        <td><?= $comparison_details['total_cancels'] ?></td>
                        <td><?= $comparison_details['total_cancelled_percent'] ?>%</td>
                        <td><?= $comparison_details['total_cancels_vs_sales'] ?></td>
                        <td><?= $comparison_details['total_ending_property_growth'] ?>%</td>
                    </tr>
                <?php } else { ?> 
                    <tr>
                        <td><?= $report_details['date_range'] ?></td>
                        <td><?= $report_details['total_starting_properties'] ?></td>
                        <td><?= $report_details['total_new_properties'] ?></td>
                        <td><?= $report_details['total_cancels'] ?></td>
                        <td><?= $report_details['total_cancelled_percent'] ?>%</td>
                        <td><?= $report_details['total_cancels_vs_sales'] ?></td>
                        <td><?= $report_details['total_ending_property_growth'] ?>%</td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
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
    <?php if(!empty($comparison_details)){?>
    <div id="chartWrapper2" >
        <canvas id="comparisonGrowthChart" height="200" width="400"></canvas>
    </div>
    <script>
        $(function(){
            var AJAX_LABELS = <?php echo json_encode($labels); ?>;
            var AJAX_DATA_SET = <?php echo json_encode($chart_data); ?>;
            var AJAX_CANCEL_DATA_SET = <?php echo json_encode($cancel_chart_data); ?>;
            var C_AJAX_LABELS = <?php echo json_encode($comparison_labels); ?>;
            var C_AJAX_DATA_SET = <?php echo json_encode($comparison_chart_data); ?>;
            var C_AJAX_CANCEL_DATA_SET = <?php echo json_encode($comparison_cancel_chart_data); ?>;
            createComparisonChart(AJAX_LABELS, AJAX_DATA_SET, AJAX_CANCEL_DATA_SET, C_AJAX_LABELS,C_AJAX_DATA_SET,C_AJAX_CANCEL_DATA_SET);
        });
    </script>
<?php } else {?>
    <div id="chartWrapper" >
        <canvas id="customerGrowthChart" height="200" width="400"></canvas>
    </div>
    <script>
        $(function(){
            var AJAX_LABELS = <?php echo json_encode($labels); ?>;
            var AJAX_DATA_SET = <?php echo json_encode($chart_data); ?>;
            var AJAX_CANCEL_DATA_SET = <?php echo json_encode($cancel_chart_data); ?>;
            createChart(AJAX_LABELS,AJAX_DATA_SET,AJAX_CANCEL_DATA_SET);
        });
</script>
    <?php } ?>
    
</div>

