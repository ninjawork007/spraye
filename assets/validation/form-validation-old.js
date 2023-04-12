
$(function() {
  jQuery.validator.addMethod("email", function(value, element) {
  // allow any non-whitespace characters as the host part
  return this.optional( element ) || /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/.test( value );
});

  $("form[name='adminlogin']").validate({
    // Specify validation rules
    rules: {      
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 5
       
      }
    },
    messages: {     
      password: {
        required: "Please provide a password",
        minlength: "Password must be at least 5 characters long",
      },
      email:{
         required : "Please provide email address",
         email :   "Please enter a valid email address",
      },
    },
    submitHandler: function(form) {
      form.submit();
    }
  });
///// add user //////////////////
  $("form[name='adduser']").validate({
      // Specify validation rules
    rules: {
      user_pic : {
        extension: "png|jpg|jpeg"
      },
      user_first_name: "required",
      user_last_name: "required",
      email: {
        required : true,
        email : true,
      },
      phone: {
        required :true,
        number : true
      },
      password: {
        required :true,
        minlength : 5
      },
      confirm_password: {
        required : true,
        equalTo : "#password"
      },
      role_id : "required"
    },
    messages: {
      user_pic : {
        extension: "Please select only image"            
      },
      user_first_name: "Please enter first name",
      user_last_name: "Please enter last name",
      email: {
        required : "Please provide email address",
        email :   "Please enter a valid email address",
      },
      phone: {
        required :"Please enter mobile number",
        number : "Please enter valid number"
      },
      password: {
        required: "Please provide a password",
        minlength: "Your password must be at least 5 characters long",
      },
      confirm_password: {
        required :   "Please provide confirm password",
        equalTo :   "Password did not match",
      },
      role_id : "Please select role",        
    },
      submitHandler: function(form) {
         form.submit();
      }
    });
///////////////////////////////////Add Customer///////////////////
    $("form[name='addcustomer']").validate({
      // Specify validation rules
      rules: {            
        first_name: "required",
        last_name: "required",
        email: {
          //required : true,
          email : true,
        },
        phone: {            
          number : true
        }, 
        home_phone: {            
          number : true
        }, 
        work_phone: {            
          number : true
        },      
        billing_street : "required",
        //billing_street_2 : "required",
        billing_city : "required",
        billing_state : "required",
        billing_zipcode : {
          required :true,
          number :true
        },
        customer_status : "required",        
        //assign_property : "required",
      },
      messages: {
        first_name: "Please enter first name",
        last_name: "Please enter last name",
        email: {
          //required : "Please provide email address",
          email :   "Please enter a valid email address",
        },
        phone: {        
          number : "Please enter valid number"
        }, 
        home_phone: {            
          number : "Please enter valid number"
        }, 
        work_phone: {            
          number : "Please enter valid number"
        }, 
        billing_street : "Please enter address",
        //billing_street_2 : "Please enter address2",
        billing_city : "Please enter city",
        billing_state : "Please enter state",
        billing_zipcode : {
          required : "Please enter zipcode",
          number : "Please enter valid number"
        },
        customer_status : "Please select customer status",        
      },
      submitHandler: function(form) {
        type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission          
          $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            success: function (response) {
              form.reset();
              $('#modal_add_customer').modal('hide');
              customerList();
              swal(
                'Customer !',
                'Added Successfully ',
                'success'
              )
              if ($(form).hasClass( "redirection" )) {
                setInterval(function() {
                    location.reload();
                  }, 2000);
              }
            }
          });
          //   return false; // required to block normal submit since you used ajax
        }
        else {
          form.submit();
        }
      }
    });
/////////////////////////////////////////Add programs////////////////////////
    $("form[name='addprogram']").validate({
      // Specify validation rules
      rules: {
        program_name: "required",
        program_price: "required",
        // program_job : "required",
        // program_notes : "required"
      },
      messages: {
        program_name: "Please enter program name",
        program_price: "Please select pricing",
        // program_job : "Please select job",
        // program_notes : "Please enter program note",        
      },
      submitHandler: function(form) {
        type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission          
          $("#loading").css("display","block");          
          $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            success: function (response) {
              form.reset();
              $('#modal_add_program').modal('hide');
              programPriceOverRide =  $('#assign_program_ids2').val();
              if (programPriceOverRide) {  
                programList(programPriceOverRide);
                setTimeout(function() { reintlizeMultiselectprogramPriceOver()(); }, 3000);
              } else {
                programList();
              }
              $("#loading").css("display","none");
              swal(
                'Program !',
                'Added Successfully ',
                'success'
              )
            }
          });
          //   return false; // required to block normal submit since you used ajax
        }
        else {          
          form.submit();
        }
      }
    });
/////////////////////////////////////Add Property///////////////////////////
    $("form[name='addproperty']").validate({
      // Specify validation rules
      rules: {            
        property_title: "required",
        property_address: "required",
        //property_address_2: "required",
        property_city: "required",
        property_state: "required",
        property_zip: {
          required :true,
          number :true
        },
        // property_area: "required",
        property_type: "required",
        yard_square_feet: {
          required : true,
          number : true
        },
        // assign_program: "required",
        assign_customer : "required",
        property_price : {
          number : true
        },
        property_status: "required"
      },
      messages: {
        property_title: "Please enter property name",
        property_address: "Please enter address",
        //property_address_2: "Please enter address2",
        property_city: "Please enter city",
        property_state: "Please enter state",
        property_zip: {
          required : "Please enter zipcode",
          number : "Please enter valid number"
        },
        // property_area: "Please select service area",
        property_type: "Please enter type",
        yard_square_feet: {
          required : "Please enter yard area",
          number : "Please enter valid number"
        },
        // assign_program: "Please select program",
        assign_customer: "Please select customer",
        property_price : {
          number : "Please enter valid number"
        }, 
        property_status: "Please select property status"
      },
      submitHandler: function(form) {
        type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
          $("#loading").css("display","block");
          $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            dataType: "json",
            success: function (response) {
              if (response.status==200) {
                $("#loading").css("display","none");
                form.reset();
                $(".summernote_property").summernote("reset");
                $('#modal_add_property').modal('hide');
                var customer_id = $('#customer_id').val();
                var multipleCutomerId = $('#multipleCutomerId').val();
                proertyPriceOverRide =  $('#assign_property_ids2').val();
                selectedProperties =  $('#property_list').val();
                // alert(selectedProperties);
                if(customer_id){  // uodate customer
                  if (selectedProperties) {
                    propertyList('',selectedProperties,response.result);
                  } else {
                    propertyList('','',response.result);
                  }
                  // propertyListSelectedByCustomer(customer_id);
                  if (multipleCutomerId) {
                    redirectLink  = window.location.origin+'/admin/editCustomer/'+customer_id+'/1';
                    window.location.href=redirectLink;                    
                  }
                } else if(proertyPriceOverRide) {  //  add program                
                  propertyList(proertyPriceOverRide);
                  setTimeout(function() { reintlizeMultiselectpropertyPriceOver()(); }, 3000);
                }else if(selectedProperties){
                  propertyList('',selectedProperties,response.result);
                }else {
                  propertyList('','',response.result);
                }
                swal(
                  'Property !',
                  'Added Successfully ',
                  'success'
                )
                if ($(form).hasClass( "redirection" )) {
                  setInterval(function() {
                    location.reload();
                  }, 2000);
                }
              } else {
                swal({
                  type: 'error',
                  title: 'Oops...',
                  text: response.msg
                })
              }
            }
          });
        }
        else {          
          form.submit();
        }        
      }
    });
/////////////////////////////////////////////Add Product//////////////////
    $("form[name='addproduct']").validate({
      // Specify validation rules
      rules: {            
        product_name: "required",
        // epa_reg_nunber: "number",
        product_cost: {
          required : true,
          number : true
        },
      // product_cost_per: "required",
        formulation: {
          // required : true,
          number : true
        },
        // formulation_per: {
        //    required : true,
        //   number :true
        // },
        // formulation_per_unit: "required",
        max_wind_speed: {
        // required : true,
          number : true
        },
        application_rate:  {
          // required : true,
          number : true
        },
        application_rate_per: {
          // required : true,
          number : true
        },           
        application_per_unit: "required",
        temperature_information:{
          // required : true,
          number : true
        },
        
        temperature_unit: "required",

      //  "active_ingredient[]": "required",
      
        "percent_active_ingredient[]": {
        // required :  true,
          number : true,
          
        },
      },

          errorPlacement: function(error, element) {
        if (element.attr("name") == "percent_active_ingredient[]") {
        error.appendTo( element.parent("div").parent("div"));

        }
        else
        {

          error.appendTo( element.parent("div"));
        }
        },

          messages: {

              product_name: "Please enter product name",
              // epa_reg_nunber: "Please enter valid number",
              product_cost :{
                required : "Please enter product cost",
                number : "Please enter valid number"
              },
            // product_cost_per : "Please enter product cost Per",
              formulation :{
                // required : "Please enter formulation",
                number : "Please enter valid number"
              }, 
              // formulation_per : {
              //   required : "Please enter formulation per",
              //   number : "Please enter valid number"
              // },
              // formulation_per_unit : "Please enter formulation unit",
              max_wind_speed : {
              // required : "Please enter wind speed",
                number : "Please enter valid number"
              },
              application_rate : {
                // required : "Please enter application rate",
                number : "Please enter valid number"
              },
              application_rate_per : {
                // required :"Please enter application rate per",
                number : "Please enter valid number"
              },
              temperature_information : {
                // required : "Please enter temperature information", 
                number : "Please enter valid number"
              }, 
              temperature_unit : "Please enter temperature unit",         
              
            // "active_ingredient[]" : "Please enter active ingredient",
      
              "percent_active_ingredient[]" : {
              //  required : "Please enter  % of active ingredient",
                number : "Please enter valid % of active ingredient",
                
                }, 
          },
          submitHandler: function(form) {
            
            type  =   $(form).attr("form_ajax");
            if(type=='ajax'){   
          
              var post_url = $(form).attr("action");
              var request_method = $(form).attr("method"); //get form GET/POST method
              var form_data = $(form).serialize(); //Encode form elements for submission
              
              $.ajax({
                    type: request_method,
                    url: post_url,
                    data: form_data,
                    success: function (response) {
                      form.reset();
                      $('#modal_add_product').modal('hide');
                      productList();
                      swal(
                                'Product !',
                                'Added Successfully ',
                                'success'
                                )


                    }
                });
              //   return false; // required to block normal submit since you used ajax
            }
            else {
        
              
              form.submit();

            }
            
          }
        });


 
///     add Job 
$("form[name='addjob']").validate({
      // Specify validation rules


      rules: {         
          job_name: "required",
          job_price: {
        required : true,
      number : true
      }
          
      },
    errorPlacement: function(error, element) {
     if (element.attr("name") == "job_price") {
         error.appendTo( element.parent("div").parent("div"));

     }
     else
     {

    error.appendTo( element.parent("div"));
     }
    },
      messages: {
          job_name: "Please enter service name",
          job_price : {
       required : "Please enter service price",
         number : "Please enter valid service price"
      }
          
      },
      submitHandler: function(form) {
          
          type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission

          $("#loading").css("display","block");
          
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                  form.reset();
                  $("#loading").css("display","none");


                  $('#modal_add_job').modal('hide');
                   viewJobList();
                   swal(
                            'Service !',
                            'Added Successfully ',
                            'success'
                            )


                 }
             });
          //   return false; // required to block normal submit since you used ajax
        }
        else {
          
           form.submit();

        }
      }
    });
//////////
/////////////////////////////////////Add Secondary Email///////////////////////////
$("form[name='add_secondary_email']").validate({
  // Specify validation rules
  rules: {
    secondary_email: {
      required :true,
      email :true
    }
  },   
  messages: {
    secondary_email: {
      required : "Please provide email address",
      email :   "Please enter a valid email address",        
    }
  },
  submitHandler: function(form) {
    type = $(form).attr("form_ajax");
    if(type=='ajax'){
      var post_url = $(form).attr("action");
      var request_method = $(form).attr("method"); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission      
      var already_added_emails = $("#secondary_email_list_hid").val();      
      form_data = `${form_data}&already_added_emails=${already_added_emails}`;      
      $("#loading").css("display","block");
      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        dataType: "json",
        success: function (response) {
          if (response.status==200) {
            $("#secondary_email_list_hid").val(response.result);
            $("#secondary_email_list").html(response.result);
            $("#loading").css("display","none");
            form.reset();
            $('#modal_add_secondary_emails').modal('hide');
            $("#reset_secondary_email_link").removeClass("hidden");
            $("#add_secondary_email_link i").removeClass("pt-5");
            swal(
              'Email !',
              'Added Successfully ',
              'success'
            )
          } else {
            swal({
              type: 'error',
              title: 'Oops...',
              text: response.msg
            })
          }
        }
      });
    }
    else {      
       form.submit();
    }    
  }
});
//////////
jQuery.validator.addMethod("checking", function(value, element,params) {
  assign_date  = $(params).val();
   current_date=formatDate();
 if(current_date == assign_date) {
    currentTime = formatTime();
    if (value <= currentTime ) {
       return false;
    } else {
      return true;
    }
  } else {
   return true;
  }  
});

jQuery.validator.addMethod("dateChecking", function(value, element) {
  // console.log(value);
  current_date=formatDate();
  if (value < current_date) {
    return false
  } else {
    return true
  }

});



///////////////////////// assign job to tecnician /////////////////
$("form[name='tecnicianjobassign']").validate({

      rules: {
         
          technician_id: "required",
          job_assign_date: "required",
          route_select: "required",
          route_input: "required",
          specific_time: {
            required : true,
            checking : '#jobAssignDate',
          },
      },
      messages: {

          technician_id: "Please select technician",
          job_assign_date: "Please select date",
          route_select: "Please select any route",
          route_input: "Please enter route name",
          specific_time: {

            required : "Please select specific time",
            checking : "Please do not select past time",

          } ,
          
      },
       errorPlacement: function(error, element) {
         if (element.attr("name") == "route_select" || element.attr("name") == "route_input" ) {
             error.appendTo('.route_error');
         }
        else if (element.attr("name") == "specific_time") {
             error.appendTo( element.parent().parent("div"));
         }
         
         else
         {
            error.appendTo( element.parent("div"));
         }
    },

      submitHandler: function(form) {

                    var group_id = [];

         $("input:checkbox[name=group_id]:checked").each(function(){
             group_id.push($(this).val());
         
         }); 
      $('#group_id').val(group_id);    

        $("#loading").css("display","block");


          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
         // alert(form_data); 
         $('#job_assign_bt').prop('disabled', true);
      
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 dataType : "JSON",
                 success: function (response) {
                 $("#loading").css("display","none");


                  form.reset();
                  $('#modal_theme_primary').modal('hide');
                  $('#job_assign_bt').prop('disabled', false);
                  if (response.status==200) {

                     swal(
                            'Service !',
                            response.msg,
                            'success'
                            )
                    location.reload(); 

                  } else if (response.status==400) {
                    swal({
                          type: 'error',
                          title: 'Oops...',
                          text: response.msg
                        
                     })


                  } else {
                    swal({
                          type: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!'
                        
                      })

                  }



                 }
             });

      }
});
////////// /////////////////////////////////////////


///////////////////////// assign job to tecnician /////////////////
$("form[name='tecnicianjobassignedit']").validate({
      rules: {
         
          technician_id: "required",
          job_assign_date: {
            required : true,
            dateChecking : true
          } ,
          route_select: "required",
          route_input: "required",
          specific_time: {
            required : true,
            checking : '#jobAssignDateEdit',
          },
      },
      messages: {

          technician_id: "Please select technician",
          job_assign_date: {
             required : "Please select date",
             dateChecking : "Please Do not select past date"
          },

          route_select: "Please select any route",
          route_input: "Please enter route name",
          specific_time: {

            required : "Please select specific time",
            checking : "Please do not select past time",

          } ,
          
      },
       errorPlacement: function(error, element) {
         if (element.attr("name") == "route_select" || element.attr("name") == "route_input" ) {
             error.appendTo('.route_edit_error');

         } else if (element.attr("name") == "specific_time") {
             error.appendTo( element.parent().parent("div"));
         } else {
            error.appendTo( element.parent("div"));
         }
    },
      submitHandler: function(form) {
        
        form.submit();

      }
});
////////// /////////////////////////////////////////


///////////////////////// assign job to tecnician /////////////////
$("form[name='dropEventForm']").validate({
      rules: {
         
          route_select: "required",
          route_input: "required",         
          specific_time: {
            required : true,
            checking : '#job_assign_date_drop',
          },

      },
      messages: {

          route_select: "Please select any route",
          route_input: "Please enter route name",
          specific_time: {

            required : "Please select specific time",
            checking : "Please do not select past time",

          } ,

                    
      },
       errorPlacement: function(error, element) {
         if (element.attr("name") == "route_select" || element.attr("name") == "route_input" ) {
             error.appendTo('.route_drop_edit_error');

         }else if (element.attr("name") == "specific_time") {
             error.appendTo( element.parent().parent("div"));
         }
         else
         {

            error.appendTo( element.parent("div"));
         }
    },
      submitHandler: function(form) {


          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission


          $.ajax({                
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 dataType: "json",          
          }).done(function(data){
             if (data.status==200) {

                  checkModalSituation = 2;                  
                   form.reset();
                  $('#modal_drop_event').modal('hide');
                   rearraangedropModalForm();
 
             }  else if (data.status==400) {
                checkModalSituation = 1
                 swal({
                          type: 'error',
                         title: 'Oops...',
                         text: data.msg
                     })

             } else {
              checkModalSituation = 1
              swal({
                          type: 'error',
                         title: 'Oops...',
                         text: 'Something went wrong!'
                 })

             } 
 

          });
        

      }
});
////////// /////////////////////////////////////////




///////////////////////// assign job to tecnician /////////////////
$("form[name='tecnicianjobassignmultipleedit']").validate({
      rules: {
         
          technician_id: "required",
          job_assign_date: "required",
          route_select: "required",
          route_input: "required",        
         
          specific_time: {
            required : true,
            checking : '#jobAssignDateEditMultiple',
          },

      },
      messages: {

          technician_id: "Please select technician",
          job_assign_date: "Please select date",
          route_select: "Please select any route",
          route_input: "Please enter route name",
          specific_time: {

            required : "Please select specific time",
            checking : "Please do not select past time",

          } ,
          
      },
      errorPlacement: function(error, element) {
         if (element.attr("name") == "route_select" || element.attr("name") == "route_input" ) {
             error.appendTo('.route_edit_multiple_error');

         }  else if (element.attr("name") == "specific_time") {
             error.appendTo( element.parent().parent("div"));
         }
         else
         {

            error.appendTo( element.parent("div"));
         }
    },
      submitHandler: function(form) {

          $("#loading").css("display","block");

          var selectcheckbox = [];
          $("input:checkbox[name=selectcheckbox]:checked").each(function(){
               selectcheckbox.push($(this).attr('technician_job_assign_ids'));
          });

          $('#multiple_technician_job_assign_id').val(selectcheckbox);    

          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission


          $.ajax({                
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 dataType: "json",          
          }).done(function(data){
    
           // alert(data);
          $("#loading").css("display","none");

             if (data.status==200) {

                    swal(
                       'Scheduled Services !',
                       'Updated Successfully ',
                       'success'
                   ).then(function() {
                    location.reload(); 
                   });
                     

                  } else if (data.status==400) {
                      swal({
                         type: 'error',
                         title: 'Oops...',
                         text: data.msg
                      })
                  } else {

                    swal({
                         type: 'error',
                         title: 'Oops...',
                         text: 'Something went wrong!'
                     })
                  }

              
          }); 


        // form.submit();

      }
});
////////// /////////////////////////////////////////

  jQuery.validator.addMethod("noSpace", function(value, element) {
      return  $.trim(value) != ""; 
}, "No space please and don't leave it empty");



///////////////////////// assign job to tecnician /////////////////
$("form[name='customeremail']").validate({

      rules: {
         
          email: {
            required : true,
            email : true
          },
          message: {
            required : true,
            noSpace : true
          }, 
      },
      messages: {

         email:{

         required : "Please provide email address",
         email :   "Please enter a valid email address",

      },

      message: {
            required : "Please enter message",
            noSpace : "Please enter  valid  message",
          },


      },
      submitHandler: function(form) {

         $('#sendmsgbt').prop('disabled', true);
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
         //alert(form_data); 
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                  form.reset();
                     $('#sendmsgbt').prop('disabled', false);
                     swal(
                            'Message !',
                            'Sent Successfully ',
                            'success'
                            )

                 }
             });

      }
});
////////// /////////////////////////////////////////


///////////////////////// job complete form /////////////////

$("form[name='completejobform']").validate({

   
    errorPlacement: function(error, element) {
       error.appendTo( element.parent("div").parent("div"));
    },
    submitHandler: function(form) {
      $('#modal_mixture_application').modal('toggle');
		
	  var basys_autocharge = document.getElementById('basys_autocharge').value;
	  var customerEmail = document.getElementById('customer_email').value;
      var email = false;
      var selected;
      var post_url = $(form).attr("action");
      var request_method = $(form).attr("method"); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission

      var program_price = document.getElementById('prog_price').value;
    
      function changeStatusFunction(invoice_id,status) {
        console.log('changing status');
        $("#loading").css("display","block");
         $.ajax({
             type: 'POST',
             url: '<?php echo base_url(); ?>admin/Invoices/changeStatus',
             data: {invoice_id: invoice_id, status: status},
             success: function (data)
             {
              $("#loading").css("display","none");
                console.log("success");
                // location.reload();

          
             }
         });
     }
          
     function bulkStatusChange(ids, status) {

      var invoice_ids = ids.split(",");
  
        $("#loading").css("display","block");
         $.ajax({
             type: 'POST',
             url: '<?php echo base_url(); ?>admin/Invoices/bulkChangeStatus',
             data: {invoice_ids: invoice_ids, status: status},
             success: function (data)
             {
              $("#loading").css("display","none");
  
                // location.reload();
  
             }
         });
      }
        
      if(program_price != 3 && basys_autocharge != 1 && customerEmail == 1) {

          //New Invoice Popup
          Swal.fire({
              title: 'Invoice',
              text: "Do you want to Email or Print an invoice now?",
              html: '<p>Would you like to email or print an invoice?</p><div class="form-check"><input class="form-check-input" type="checkbox" value="1" id="email"><label class="form-check-label" for="email">&nbsp;&nbsp;Email</label></div><div class="form-check"><input class="form-check-input" type="checkbox" value="2" id="print"><label class="form-check-label" for="print">&nbsp;&nbsp;Print</label></div>',
              preConfirm: () => { 
                  
                  selected = [
                      document.getElementById('email').checked, 
                      document.getElementById('print').checked
                    
                  ];
                  
                  return [
                    document.getElementById('email').checked,
                    document.getElementById('print').checked 
                  ]
              },
          }).then((result) => {

                   $("#loading").css("display","block");
    
                      $.ajax({
                         type: request_method,
                         url: post_url,
                         data: form_data,                              
                         cache: false,
                         dataType: "json",
                         success: function (data){
                      
                           $("#loading").css("display","none");

                            if (data.status==200) { 
                                /** 
                                * 0: email
                                * 1: print
                                **/
                              if(selected[0] && selected[1]){
  
                                    Swal.fire({
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        html:'<p>You will be redirected back to the dashboard after proceeding.</p><a href="'+ data.inv_url + '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>'
                                    })//.then((result) => {
//                                        window.location = data.email_url;
//                                    })
                                  function redirect(){
                                    setTimeout(function(){
                                      window.location = data.email_url;
                                    }, 3000);
                                  }
                                  var btn = document.getElementById("myBtn"); btn.addEventListener("click", redirect);
                                   // window.location = data.email_url;

                              } else if(selected[0]) {      
                                  // console.log(data.invoice_id_nums);
                                  // console.log(data.sent_status)

                                  // bulkStatusChange(data.invoice_id_nums, 1); //sent status 1: Sent 
                                    window.location = data.email_url;
                              
                              } else if(selected[1]) {  
                                  Swal.fire({
                                      showConfirmButton: false,
                                      allowOutsideClick: false,
                                        html:'<p>You will be redirected back to the dashboard after proceeding.</p><a href="'+ data.inv_url + '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>'
                                    })
                                    function redirect(){
                                      setTimeout(function(){
                                        window.location = data.email_url;
                                      }, 3000);
                                    }
                                  var btn = document.getElementById("myBtn"); btn.addEventListener("click", redirect);
                              } else {           
                                  window.location = data.url;
                              }
  
                            } else if (data.status==400) {
  
                                  window.location = data.url;
  
                            }
  
                          }
                        });
  
        })
    
	  } else if(program_price != 3 && basys_autocharge != 1) {
          //Old Invoice Popup
        Swal.fire({
         title: 'Invoice',
          text: "Do you want to print an invoice now?",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#009402',
          cancelButtonColor: '#FFBE2C',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No'
    
         }).then((result) => {          
                     
                  $("#loading").css("display","block");   
                     $.ajax({
                        type: request_method,
                        url: post_url,
                        data: form_data,                              
                         dataType: "json",
                         success: function (data){
                            $("#loading").css("display","none");                             
                           if (data.status==200) {  
                             if (result.value) {
                                  window.open(data.inv_url);
                                  window.location = data.url;
							 } else {           
                                   window.location = data.url;            
                             }
  
                            } else if (data.status==400) {
  
                                 window.location = data.url;
 
                            }
  
                          }
                       });
 
        })
      } else {
        $("#loading").css("display","block");
    
        $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,                              
            dataType: "json",
            success: function (data){
        
                $("#loading").css("display","none");


                console.log(data);
              
                // alert(data.status);
                //alert(result.data);
                if (data.status==200) { 

                    if (result.value) {
                        window.open(data.inv_url);
                        window.location = data.url;
        
                    } else {           
                        window.location = data.url;            
                    }

                } else if (data.status==400) {

                    window.location = data.url;

                }

            }
        });
      }
      


    }
});
////////// /////////////////////////////////////////


$.validator.addMethod( "lessThanEqualMethod", function( value, element, param ) {
       var target =  Number( $( param ).val() );
       var value = Number(value);
       if (target<value) {
           return false;
       } else {
           return true;
       }

    // return value <= target.val();
} );

$.validator.addMethod( "greaterThanEqualMethod", function( value, element, param ) { 
  var target =  Number( param );
  var value = Number(value);
  
  if (target>value) {
      return false;
  } else {
      return true;
  }

// return value <= target.val();
} );

$.validator.addMethod( "greaterThan", function( value, element, param ) {
       var target =  Number( param );
       var value = Number(value);
 
       if (value>target) {
           return true;
       } else {
           return false;
       }

    // return value <= target.val();
} );





////////////////////////////  invoice    ////////////////////
$("form[name='addinvoice']").validate({

      rules: {
         customer_id : "required",
          /*stomer_email: {
            required : true,
            email : true
          },*/
          property_id: "required",
          invoice_date : "required",
          // description  : "required",
          cost  : {

              required :true,
              number : true,
              greaterThanEqualMethod: 0
          },
          // job_id : "required", 
          program_id : "required",
          partial_payment : {
            number : true,
             greaterThan: 0,
            lessThanEqualMethod  : "#over_all_total",
          }  
      },

     errorPlacement: function(error, element) {
     if (element.attr("name") == "cost" || element.attr("name") == "over_all_total" || element.attr("name") == "partial_payment" || element.attr("name") == "balance_due" ) {
         error.appendTo( element.parent("div").parent("div"));

     }
     else
     {

    error.appendTo( element.parent("div"));
     }
    },
    
      messages: {
        customer_id : "Please select customer",

         customer_email:{

         required : "Please provide email address",
         email :   "Please enter a valid email address",

      },
       property_id: "Please select property address",
       invoice_date: "Please select invoice date",
       // description : "Please enter description",
       cost : {
        required :  "Please enter cost",
        number : "Please enter valid number",
        greaterThanEqualMethod : "Please enter positive value"
       },
       // job_id : "Please select job",
       program_id : "Please select program",
       partial_payment : {
           number : "Please enter valid number",
           greaterThan : "Please enter a value greater than 0",
           lessThanEqualMethod  : "Value should be less than or equal to total cost value"
          }  

      },
      submitHandler: function(form){
        form.submit();          

      }
});

///////////////////////////// end invoice //////////////////




////////////////////////////  invoice    ////////////////////
$("form[name='addestimate']").validate({

      rules: {
         customer_id : "required",
         // customer_email:{
         //   required :true,
         //   email :   true,
         // },         
         property_id: "required",
         estimate_date : "required",
        
        program_id : "required",
      },



    errorPlacement: function(error, element) {
          error.appendTo( element.parent("div"));
  
    },
    


      messages: {
        customer_id : "Please select customer",

      //    customer_email:{

      //    required : "Please provide email address",
      //    email :   "Please enter a valid email address",

      // },
       property_id: "Please select property address",
       estimate_date : "Please select estimate date",
       invoice_date: "Please select invoice date",
       // description : "Please enter description",
       cost : {
        required :  "Please enter cost",
        number : "Please enter valid number",
        greaterThan : "Please enter a value greater than 0"
       },
       // job_id : "Please select job",
       program_id : "Please select program",
       partial_payment : {
           number : "Please enter valid number",
           greaterThan : "Please enter a value greater than 0",
           lessThanEqualMethod  : "Value should be less than or equal to total cost value"
          }  

      },
      submitHandler: function(form){
        form.submit();          

      }
});

///////////////////////////// end invoice //////////////////


///////////////////////////// company details  //////////////////

  $("form[name='estimatesetting']").validate({
      // Specify validation rules
      // rules: {         
      //     tearm_condition: "required",      
      // },
      // messages: {

      //     tearm_condition: "Please enter tearm & condition",     
      // },
      submitHandler: function(form) {
         form.submit();

      }
    });



///////////////////////////// end company details ///////////



///////////////////////////// company details  //////////////////

  $("form[name='companydetails']").validate({
      // Specify validation rules
      rules: {         
          company_name: "required",
          company_address: "required",
          company_phone_number: {
            required :true,
            phoneUS : true
          },
          company_email: {
            required : true,
            email : true,
          },
          web_address : {
            // required  : true,
            url : true,
          },
          invoice_color : "required",
          default_display_length : {
            required : true,
            number : true,
            min : 1
          }
      },
      messages: {

          company_name: "Please enter company name",
          company_address: "Please enter company address",
          company_phone_number: {
            required :"Please enter mobile number",
            phoneUS : "Please enter valid number"
          },
          company_email: {
             required : "Please provide email address",
             email :   "Please enter a valid email address",
          },

           web_address : {
            // required  : "Please enter web address",
            url : "Please enter a valid url",
          },

          invoice_color : "Please select invoice color",

          default_display_length : {
            required : "Please enter company name default # of entries",
          }

      },
      submitHandler: function(form) {
         form.submit();

      }
    });



///////////////////////////// end company details ///////////
///////////////////////////// start quich book ///////////
var OAuthCode = function(url) {
  
  this.loginPopup = function(parameter) {
    this.loginPopupUri(parameter);
  }
  this.loginPopupUri = function(parameter) {
    // Launch Popup
    var parameters = "location=1,width=800,height=650";
    parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;
    var win = window.open(url, 'connectPopup', parameters);
    var pollOAuth = window.setInterval(function() {
      try {
        if (win.document.URL.indexOf("code") != -1) {
          window.clearInterval(pollOAuth);
          win.close();
          location.reload();
        }
      } catch (e) {
        console.log(e)
      }
    }, 100);
  }
}
  $("form[name='quickbookauth']").validate({
      // Specify validation rules
      rules: {         
             
        
          quickbook_client_id : "required",
          quickbook_client_secret : "required",
      },
      messages: {

          quickbook_client_id : "Please enter client id",
          quickbook_client_secret : "Please enter client secret"
         
      },
      submitHandler: function(form) {

           var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
              // $("#loading").css("display","block");
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                //  alert(response);
                  if (response) {
                   var url = response;
                   console.log(url);

                     var oauth = new OAuthCode(url);

                       oauth.loginPopup();
                               
                  }
                  // $("#loading").css("display","none"); 
                 }
             });
                 

      }
    });



///////////////////////////// end quich book ///////////



///////////////////////////// setting //////////////////

  $("form[name='settings']").validate({
      // Specify validation rules
      rules: {         
             
        
          start_location : "required",
          end_location : "required",
      },
      messages: {

          start_location : "Please enter start location",
          end_location : "Please enter end location"
         
      },
      submitHandler: function(form) {
         form.submit();

      }
    });


///////////////////////////// end setting //////////////////




///////////////////////////// invoice details //////////////////

  $("form[name='invoicedetails']").validate({
      // Specify validation rules
      rules: {         
             
          payment_terms : "required",
          pay_now_btn_link : {
            required : true,
            url : true,
          },

          convenience_fee : {
            number : true,
          },

        
      },

  errorPlacement: function(error, element) {
     if (element.attr("name") == "convenience_fee"  ) {
      error.appendTo( element.parent("div").parent("div"));
     }
     else{
      error.appendTo( element.parent("div"));
     } 
   },

       messages: {
          payment_terms : "Please select any payment terms",          
          pay_now_btn_link : {
            required : "Please enter your pay link here",
            url : "Please enter a valid URL"
          },
      },



      submitHandler: function(form) {
         form.submit();

      }
    });


///////////////////////////// end invoice details //////////////////





///////////////////////////// invoice details //////////////////

  $("form[name='basysintrigation']").validate({
      // Specify validation rules
      rules: {         
             
          api_key : "required",
        
      },
       messages: {
          api_key : "Basys api key is required",          
       
      },
      submitHandler: function(form) {
         form.submit();

      }
    });


///////////////////////////// end invoice details //////////////////






////////////////////////////  service area    ////////////////////
$("form[name='addservicearea']").validate({

      rules: {
         category_area_name : "required"
      },
    
      messages: {
        category_area_name : "Please enter service area name",
      },
      submitHandler: function(form){
       
         type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
              $("#loading").css("display","block");
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                  form.reset();
                  $('#modal_add_service_area').modal('hide');
                  $("#loading").css("display","none"); 
                   swal(
                           'Service Area',
                           'Added Successfully ',
                           'success'
                          )
                    getServiceAreaOption();
                   getServiceAreaList();
                 //  alert(response);
                   


                 }
             });
          //   return false; // required to block normal submit since you used ajax
        }
        else {
          
           form.submit();
           
        }


      }
});

///////////////////////////// end service area //////////////////


//////////////////////////  service area    ////////////////////
$("form[name='addsalestexarea']").validate({

      rules: {
         tax_name : "required",
         tax_value : {
          required : true,
          number : true,
          min : 0
         }
      },
    
      messages: {
        tax_name : "Please enter sales tax area name",
         tax_value : {
          required : "Please enter sales tax area percentage",
          number : "Please enter valid number",
          min : "Please enter minimum 0 value"
         }
      },
      submitHandler: function(form){
       
         type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
              $("#loading").css("display","block");
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                  form.reset();
                  $('#modal_add_sale_tax_area').modal('hide');
                  $("#loading").css("display","none"); 
                   swal(
                           'Sales Tax Area',
                           'Added Successfully ',
                           'success'
                          )
                   getSalesTexAreaList()
                 }
             });
          //   return false; // required to block normal submit since you used ajax
        }
        else {
          
           form.submit();
           
        }


      }
});

///////////////////////////// end service area //////////////////



//////////////////////////  service area    ////////////////////
$("form[name='editsalestexarea']").validate({

      rules: {
         tax_name : "required",
         tax_value : {
          required : true,
          number : true,
          min : 0
         }
      },
    
      messages: {
        tax_name : "Please enter sales tax area name",
         tax_value : {
          required : "Please enter sales tax area percentage",
          number : "Please enter valid number",
          min : "Please enter minimum 0 value"
         }
      },
      submitHandler: function(form){
       
         type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
              $("#loading").css("display","block");
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                  form.reset();
                  $('#modal_sales_tax_area').modal('hide');
                  $("#loading").css("display","none"); 
                   swal(
                           'Sales Tax Area',
                           'Updated Successfully ',
                           'success'
                          )
                   getSalesTexAreaList()
                 }
             });
          //   return false; // required to block normal submit since you used ajax
        }
        else {
          
           form.submit();
           
        }


      }
});

///////////////////////////// end service area //////////////////



////////////////////////////  edit service area    ////////////////////
$("form[name='editservicearea']").validate({

      rules: {
         category_area_name : "required"
      },
    
      messages: {
        category_area_name : "Please enter service area name",
      },
      submitHandler: function(form){
       
         type  =   $(form).attr("form_ajax");
        if(type=='ajax'){
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
         $("#loading").css("display","block");
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 success: function (response) {
                  form.reset();
                  $('#modal_edit_service_area').modal('hide');
                  $("#loading").css("display","none"); 
                 // alert(response);
                   if (response==1) {                  
                      swal(
                               'Service Area',
                               'Updated Successfully ',
                               'success'
                         )                 
                   }
                   getServiceAreaList();      


                 }
             });
            return false; // required to block normal submit since you used ajax
        }
        else {
          
           form.submit();
           
        }


      }
});

///////////////////////////// edit end service area //////////////////

///////////////////////////////add csv file //////////////////////////

$("form[name='csvfileimport']").validate({

      rules: {
        csv_file : {
          required : true,
          extension: "csv"            
        }
      },
    
      messages: {

       csv_file : {
          required : "Please select file",
          extension:"Please select only csv files"        
       }
      },
      submitHandler: function(form){
        form.submit();
     
      }
});

///////////////////////////////endcsv file //////////////////////////


///////////////////////////////Email automated //////////////////////////

$("form[name='automatedemail']").validate({

      rules: {
        job_sheduled : "required",
        one_day_prior : "required",
        job_completion : "required"        
      },
    
      messages: {
        job_sheduled : "Please enter job Sheduled",
        one_day_prior : "Please enter 1 day prior to scheduled date",
        job_completion : "Please enter job completion",
       
      },
      submitHandler: function(form){
        form.submit();
     
      }
});

///////////////////////////////endcsv file //////////////////////////


///////////////////////////////Email automated //////////////////////////

$("form[name='smtpcredential']").validate({

      rules: {
       
        smtp_host : "required",
        smtp_port : {
          required : true,
          number : true,
        },
        smtp_username : "required",
        smtp_password : "required",
      },
    
      messages: {
      
        smtp_host : "Please enter smtp host",
        smtp_port : {
          required : "Please enter smtp port",
          number : "Please enter valid port number",
        },
        smtp_username : "Please enter smtp username",
        smtp_password : "Please enter smtp password",
      },
      submitHandler: function(form){
        form.submit();
     
      }
});

///////////////////////////////endcsv file //////////////////////////




//////////////////////////  service area    ////////////////////
$("form[name='HelpMessageForm']").validate({

      rules: {
         message : {
          required : true,
           normalizer: function( value ) {
              // Trim the value of the `field` element before
              // validating. this trims only the value passed
              // to the attached validators, not the value of
              // the element itself.
              return $.trim( value );
            },
         },
      },
    
      messages: {
       message : {
          required : "Please enter message",
         },
      },
      submitHandler: function(form){

          
          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
          var form_data = $(form).serialize(); //Encode form elements for submission
           $("#loading").css("display","block");
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: form_data,
                 dataType: "JSON",
                 success: function (response) {
                  $("#loading").css("display","none"); 
                  if (response.status==200) {
                    form.reset(); 
                  $('#help_message').modal('hide');
                  
                   swal(
                           'Help Message',
                           'sent successfully ',
                           'success'
                       )

                  } else if (response.status==400) {
                     swal({
                         type: 'error',
                         title: 'Oops...',
                         text: response.msg
                      })

                  } else {
                     swal({
                         type: 'error',
                         title: 'Oops...',
                         text: 'Something went wrong!'
                     })

                  }
        
                 }
             });
          //   return false; // required to block normal submit since you used ajax
        

      }
});

///////////////////////////// end service area //////////////////




///////////////////////// assign job to tecnician /////////////////
$("form[name='assignProgram']").validate({

      rules: {         
          program_id: "required",          
      },
      messages: {
          program_id: "Please select program",
      },
  
      submitHandler: function(form) {
           $("#loading").css("display","block");

        var property_ids = [];
   
          $("input:checkbox[name=selectcheckbox]:checked").each(function(){
               property_ids.push($(this).attr('property_id'));
          }); 

         var program_id =  $("#selected_program_id").val();



          var post_url = $(form).attr("action");
          var request_method = $(form).attr("method"); //get form GET/POST method
      
          $.ajax({
                 type: request_method,
                 url: post_url,
                 data: {property_ids : property_ids,program_id : program_id },
                 dataType : "JSON",
                 success: function (response) {
                 $("#loading").css("display","none");
                  form.reset();
                  $('#modal_assign_program').modal('hide');
                  if (response.status==200) {
                     swal(
                            'Program !',
                            response.msg,
                            'success'
                            ).then(function() {
                              location.reload();
                            });
                   

                  }  else {
                    swal({
                          type: 'error',
                          title: 'Oops...',
                          text: 'Something went wrong!'
                        
                      })

                  }



                 }
             }); 

      }
});
////////// /////////////////////////////////////////


      jQuery.validator.addMethod("checkedTechnicain", function(value, element) {
         var isSuccess = false;           
        $.ajax({
            type: 'POST',
            url:  location.origin+'/admin/Managesubscription/getTechCount',
            async: false, 
            dataType : 'JSON',            
            success: function (response) {
                if (response.status==200) {

                      if(Number(response.result) > Number(value)+1) {
                        console.log(Number(response.result))
                        console.log(Number(value)+1)
                         isSuccess  = false;
                      } else {
                        console.log(Number(response.result))
                        console.log(Number(value)+1)
                          isSuccess  =  true;
                      }                 
                    
                } else {
                 
                    isSuccess  = false;
                }
            }
          });
         return isSuccess;        
    });


 jQuery.validator.addMethod("checkboxCheck", function(value, element,param) {
      
         if($(param).prop("checked") == true && value <=0 ){       
            return false;
         } else {
            return true;
         }

    });


///////////////////////////////add csv file //////////////////////////

$("form[name='updateplan']").validate({

      rules: {
        is_technician_count : {
            integer: true,
            checkedTechnicain : true,
            max : 1000, 
            checkboxCheck : "#is_additional",            
                        
        }
      },

      messages: {
       
        is_technician_count : {
            integer : "Please enter only integer value",
            checkedTechnicain : "You have already exists more than technician",
            checkboxCheck : "Please enter number of additional technician",

        },
      },        
      submitHandler: function(form){
        form.submit();
     
      }
});

///////////////////////////////endcsv file //////////////////////////





});


$(document).ready(function () {

    $("input").on("keypress", function(e) {
      
         if (e.which === 32 && !this.value.length)
            e.preventDefault();
    });
  });



 $.validator.setDefaults({
      ignore: ":hidden" // validate all hidden select elements
  });
 
