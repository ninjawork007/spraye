<div class="content">
  <div class="panel panel-flat">
    <div class="panel-body">
  <form method="POST" action="<?= base_url('inventory/Backend/Customers/AddBatchCredit') ?>">
   <div class="row">
      <div class="col-lg-2">Customer</div>
      <div class="col-lg-2">Amount</div>
      <div class="col-lg-2">Payment Type</div>
      <div class="col-lg-2">Check Number</div>
      <div class="col-lg-1">Due</div>
      <div class="col-lg-1">Balance Due</div>
      <div class="col-lg-2"></div>
   </div>

   <?php
   foreach($credits as $index => $Crds){
    ?>

     <div class="row" id="BatchRow<?php echo $index ?>">
        <div class="col-lg-2" id="autocomplete-container-<?php echo $index ?>">
           <input class="form-control CusInxBox" id="SearchCustomerBox-<?php echo $index ?>" required spellcheck="true" name="customer_name[]" value="<?php echo $Crds['Customer'] ?>">
           <ul class="dropdown-menu" id="itemSuggestions-<?php echo $index ?>"></ul>
        </div>
        <div class="col-lg-2">
           <input class="form-control CusInxBoxAmount" required onchange="getAll()" onblur="getAll()" type="number" step="0.01" maxlength="100" size="100" spellcheck="true" name="BatchAmount[]" data-index="<?php echo $index ?>" value="<?php echo $Crds['Amount'] ?>">
        </div>
        <div class="col-lg-2">
           <select class="form-control" name="payment_type[]">
              <option <?php if(strtolower($Crds['PaymentType']) == "check") { echo 'selected'; } ?> value="check">Check</option>
              <option <?php if(strtolower($Crds['PaymentType']) == "cash") { echo 'selected'; } ?> value="cash">Cash</option>
              <option <?php if(strtolower($Crds['PaymentType']) == "other") { echo 'selected'; } ?> value="other">Other</option>
           </select>
        </div>
        <div class="col-lg-2">
           <input class="form-control" type="text" spellcheck="true" name="BatchReason[]" value="<?php echo $Crds['Reference'] ?>">
        </div>
        <div class="col-lg-1" id="TotalDueDiv-<?php echo $index ?>">0.0</div>
        <div class="col-lg-1" id="TotalBalanceDueDiv-<?php echo $index ?>">0.0</div>
        <div class="col-lg-2">
           <button class="btn btn-danger mt-5 mb-5" onclick="RemoveBatchRow('BatchRow<?php echo $index ?>')" type="button"> - Remove</button>
        </div>
     </div>
     <?php
   }
   ?>

   <div id="LoadBathchRowNew"></div>
   <button onclick="AddMoreRowBatch()" class="btn btn-primary mt-5 mb-5" type="button"><i class="icon-plus22"></i> Add More</button>

   <h5>Total</h5>
   <div class="row">
      <div class="col-lg-3" id="ShowTotalNoCustomers"><?php echo count($credits) ?></div>
      <div class="col-lg-3" id="ShowTotalNoAmount">0.0</div>
      <div class="col-lg-3"></div>
      <div class="col-lg-3"></div>
   </div>

    <div class="col">
      <div class="modal-footer">
        <button class="btn btn-primary" type="submit">Submit</button>
      </div>
    </div>
  </form>
</div>
</div>
</div>


<script>
     var counter = <?php echo count($credits) ?>;
   function AddMoreRowBatch(){
      counter++;
      var HTML = "";
      HTML +='<div class="row" id="BatchRow'+counter+'">';
      HTML +='<div class="col-lg-2" id="autocomplete-container-'+counter+'">';
      HTML +='<ul class="dropdown-menu" id="itemSuggestions-'+counter+'"></ul>';
      HTML +='<input class="form-control CusInxBox" id="SearchCustomerBox-'+counter+'" required spellcheck="true" name="customer_name[]">';
      HTML +='</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<input class="form-control CusInxBoxAmount" onchange="getAll()" onblur="getAll()" required type="number" step="0.01" maxlength="100" size="100" spellcheck="true" name="BatchAmount[]" data-index="'+counter+'">';
      HTML +='</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<select class="form-control" name="payment_type[]">';
      HTML +='<option selected value="check">Check</option>';
      HTML +='<option value="cash">Cash</option>';
      HTML +='<option value="other">Other</option>';
      HTML +='</select>';
      HTML +='</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<input class="form-control" type="text" spellcheck="true" name="BatchReason[]">';
      HTML +='</div>';
      HTML +='<div class="col-lg-1" id="TotalDueDiv-'+counter+'">0.0</div>';
      HTML +='<div class="col-lg-1" id="TotalBalanceDueDiv-'+counter+'">0.0</div>';
      HTML +='<div class="col-lg-2">';
      HTML +='<button class="btn btn-danger mt-5 mb-5" onclick=RemoveBatchRow("BatchRow'+counter+'") type="button"> - Remove</button>';
      HTML +='</div>';
      HTML +='</div>';

      $("#LoadBathchRowNew").append(HTML);

      $('#SearchCustomerBox-'+counter).on('focus', e => {
         $('#autocomplete-container-'+counter).addClass('open')
      })

      $('#SearchCustomerBox-'+counter).on('blur', e => {
         // Timeout so that the item clicked listener can fire
         setTimeout(() => {
            $('#autocomplete-container-'+counter).removeClass('open')
         }, 200)
      })

      // Listen for changes on the autocomplete input
      $('#SearchCustomerBox-'+counter).on('input', e => {
         autocomplete(e.target.value, counter);
         getAll();
      })

      $('ul#itemSuggestions-'+counter).on('click', 'li', e => {
         let id = $(e.currentTarget).data('item-id')
         $('#SearchCustomerBox-'+counter).val(id);
         getDueAmount(id, counter);
         getAll();
      })
   }

  function RemoveBatchRow(id){
      $("#"+id).remove();
      getAll();
   }

   function getAll(){
      var totalCustomers = 0;
      var totalAmount = 0;
      $(".CusInxBox").each(function () {
         if($(this).val() != ""){
            totalCustomers += 1;
         }
      });

      $(".CusInxBoxAmount").each(function () {
         if($(this).val() != ""){
            OldDue = $("#TotalDueDiv-"+$(this).data("index")).html();
            OldDue = parseFloat(OldDue);

            NewDue = OldDue - parseFloat($(this).val());
            $("#TotalBalanceDueDiv-"+$(this).data("index")).html(NewDue);

            totalAmount += parseFloat($(this).val());
         }
      });

      $("#ShowTotalNoCustomers").html(totalCustomers);
      $("#ShowTotalNoAmount").html(totalAmount);
   }

    function getDueAmount(id, index){
      var url = '<?= base_url('inventory/Backend/Customers/DueAmount/') ?>'+id;
      var request_method = "GET";
      $.ajax({
         type: request_method,
         url: url,
         dataType:'JSON', 
         success: function(response){
            $("#TotalDueDiv-"+index).html(response);
            $("#TotalBalanceDueDiv-"+index).html(response);
            getAll();
         }
      });
   }

   function autocomplete(search, counter) {
      var url = '<?= base_url('inventory/Backend/Customers/Search') ?>';
      var request_method = "GET";
      $.ajax({
         type: request_method,
         url: url,
         data: {search: search},
         dataType:'JSON', 
         success: function(response){
            $('ul#itemSuggestions-'+counter).empty()
            response.result.forEach(item => {
            let elem = `<li data-item-id="${item.customer_id}">`
               + `<span class="item-name">${item.first_name} ${item.last_name} - ${item.customer_id}</span>`
               + '</li>'

            $('ul#itemSuggestions-'+counter).append(elem)
            })
         }
      });
   }


<?php
   foreach($credits as $index => $Crds){
    ?>
   // When focusing on the autocomplete, show list
   $('#SearchCustomerBox-<?php echo $index?>').on('focus', e => {
      $('#autocomplete-container-<?php echo $index?>').addClass('open')
   })

   $('#SearchCustomerBox-<?php echo $index?>').on('blur', e => {
      setTimeout(() => {
         $('#autocomplete-container-<?php echo $index?>').removeClass('open')
      }, 200)
   })

   // Listen for changes on the autocomplete input
   $('#SearchCustomerBox-<?php echo $index?>').on('input', e => {
      autocomplete(e.target.value, <?php echo $index?>);
      getAll();
   })

   $('ul#itemSuggestions-<?php echo $index?>').on('click', 'li', e => {
      let id = $(e.currentTarget).data('item-id')
      $('#SearchCustomerBox-<?php echo $index?>').val(id);
      getDueAmount(id, <?php echo $index?>);
      getAll();
   })

   getDueAmount(<?php echo $Crds['Customer'] ?>, <?php echo $index ?>);
   <?php
 }
 ?>
</script>