<table class="table datatable-button-print-basic">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Date</th>
            <th>User</th>
            <th>Amount</th>
            <th>Payment Type</th>
            <th>Note</th>
            <th>Responsible Party</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($invoices as $Invs){
            $CustomerData = $this->db->select('*')->from("customers")->where(array("customer_id" => $Invs->customer_id))->get()->row();

            $ResponsibleParty = "";
            $Part = explode(",", $Invs->responsible_party);

            foreach($Part as $PP){
                $PartData = $this->db->select('*')->from("users")->where(array("id" => $PP))->get()->row();
                $ResponsibleParty .= @$PartData->user_first_name." ".@$PartData->user_last_name.", ";
            }
        ?>
        <tr>
            <td><?php echo $CustomerData->first_name. " " . $CustomerData->last_name ?></td>
            <td><?php echo date("d F, Y", strtotime($Invs->invoice_created)) ?></td>
            <td><?php echo $Invs->user_first_name. " " . $Invs->user_last_name ?></td>
            <td><?php echo $Invs->credit_amount ?></td>
            <td>
                <?php 
                if($Invs->payment_method == 0){
                    echo "Cash";
                }
                if($Invs->payment_method == 1){
                    echo "Check";
                }
                if($Invs->payment_method == 3){
                    echo "Other";
                }
                ?>
            </td>
            <td><?php echo $Invs->credit_notes ?></td>
            <td><?php echo $ResponsibleParty ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>