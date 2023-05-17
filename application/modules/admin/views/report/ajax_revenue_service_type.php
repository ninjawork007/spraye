<div class="post-list" id="invoice-age-list" <?php if(empty($report_details)){ ?> style="padding-top:20px" <?php } ?> >
    <div class="table-responsive table-spraye">
        <table class="table datatable-button-print-basic">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Service Type</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($Services as $Index => $ServiceName) {
                    $ServiceTypeName = "";
                    if($Index == 0 || $Index == ""){
                        $ServiceTypeName = "NONE SELECTED   ";
                    }else{
                        $Serv = $this->db->select('*')->from("service_type_tbl")->where(array("service_type_id" => $Index))->get()->row();
                        $ServiceTypeName = $Serv->service_type_name;
                    }
                ?>
                <tr>
                    <td><?php echo date("m/d/Y", strtotime($StartDate)) . " TO " . date("m/d/Y", strtotime($EndDate)) ?></td>
                    <td><?php echo $ServiceTypeName ?></td>
                    <td><?php echo $ServiceName ?></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>