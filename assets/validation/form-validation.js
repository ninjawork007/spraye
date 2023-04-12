$(function () {
  // ADD VALIDATOR METHODS
  jQuery.validator.addMethod('email', function (value, element) {
    // allow any non-whitespace characters as the host part
    return (
        this.optional(element) ||
        /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/.test(value)
    );
  });
  jQuery.validator.addMethod('checking', function (value, element, params) {
    assign_date = $(params).val();
    current_date = formatDate();
    if (current_date == assign_date) {
      currentTime = formatTime();
      if (value <= currentTime) {
        return false;
      } else {
        return true;
      }
    } else {
      return true;
    }
  });

  jQuery.validator.addMethod('dateChecking', function (value, element) {
    // console.log(value);
    current_date = formatDate();
    if (value < current_date) {
      return false;
    } else {
      return true;
    }
  });
  jQuery.validator.addMethod(
      'noSpace',
      function (value, element) {
        return $.trim(value) != '';
      },
      "No space please and don't leave it empty"
  );

  $.validator.addMethod(
      'lessThanEqualMethod',
      function (value, element, param) {
        var target = Number($(param).val());
        var value = Number(value);
        if (target < value) {
          return false;
        } else {
          return true;
        }

        // return value <= target.val();
      }
  );

  $.validator.addMethod(
      'greaterThanEqualMethod',
      function (value, element, param) {
        var target = Number(param);
        var value = Number(value);

        if (target > value) {
          return false;
        } else {
          return true;
        }

        // return value <= target.val();
      }
  );

  $.validator.addMethod('greaterThan', function (value, element, param) {
    var target = Number(param);
    var value = Number(value);

    if (value > target) {
      return true;
    } else {
      return false;
    }

    // return value <= target.val();
  });
  jQuery.validator.addMethod('checkedTechnicain', function (value, element) {
    var isSuccess = false;
    $.ajax({
      type: 'POST',
      url: location.origin + '/admin/Managesubscription/getTechCount',
      async: false,
      dataType: 'JSON',
      success: function (response) {
        if (response.status == 200) {
          if (Number(response.result) > Number(value) + 1) {
            console.log(Number(response.result));
            console.log(Number(value) + 1);
            isSuccess = false;
          } else {
            console.log(Number(response.result));
            console.log(Number(value) + 1);
            isSuccess = true;
          }
        } else {
          isSuccess = false;
        }
      },
    });
    return isSuccess;
  });

  jQuery.validator.addMethod('checkboxCheck', function (value, element, param) {
    if ($(param).prop('checked') == true && value <= 0) {
      return false;
    } else {
      return true;
    }
  });

  /* 
    Methods to validate US Zip Codes and CA Postal Codes 
   */
  $.validator.addMethod( "validationUSzipcodeCApostcode", function( value, element ) {
      var zipPostCodeValue = value.toString().trim().replace(/ /g,'')

      if(this.optional(element))
      { return true; }
      else if(isValidPostCodeCA(zipPostCodeValue))
      { 
        element.value = formatPostCodeCA(zipPostCodeValue);
        return true;
      }
      else if(isValidZipCodeUS(zipPostCodeValue)) 
      { return true;}     
      else{ return false;}

      return false;    
  }, "Please specify a valid postal or zip code" );

  function isValidZipCodeUS(value)
  {
    if(!value){return false;}

    nonValidatedValue = value.toString().trim().replace(/ /g,'')  
    var usaZipCodeRegExp = new RegExp("^\\d{5}(-{0,1}\\d{4})?$");    

    if(usaZipCodeRegExp.test(nonValidatedValue)){return true;}    
    return false;
  }

  function isValidPostCodeCA(value)
  {
    if(!value){return false;}

    nonValidatedValue = value.toString().trim().replace(/ /g,'')
    var caZipCodeRegExp = new RegExp(/^[ABCEGHJ-NPRSTVXY]\d[ABCEGHJ-NPRSTV-Z][ -]?\d[ABCEGHJ-NPRSTV-Z]\d$/i);

    if ( caZipCodeRegExp.test(nonValidatedValue) ){ return true;}    
    return false;
  }

  function formatPostCodeCA(value)
  { 
    nonFormattedValue = value.toString().trim().replace(/ /g,'');
    return value.replace(/^(.*)(.{3})$/,'$1 $2').toUpperCase(); 
  }



  /// END ADD METHODS///
  // ADMIN LOGIN FORM
  $("form[name='adminlogin']").validate({
    // Specify validation rules
    rules: {
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 5,
      },
    },
    messages: {
      password: {
        required: 'Please provide a password',
        minlength: 'Password must be at least 5 characters long',
      },
      email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // CUSTOMER LOGIN FORM
  $("form[name='customerslogin']").validate({
    // Specify validation rules
    rules: {
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 5,
      },
    },
    messages: {
      password: {
        required: 'Please provide a password',
        minlength: 'Password must be at least 5 characters long',
      },
      email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // ADD USER FORM
  $("form[name='adduser']").validate({
    // Specify validation rules
    rules: {
      user_pic: {
        extension: 'png|jpg|jpeg',
      },
      user_first_name: 'required',
      user_last_name: 'required',
      email: {
        required: true,
        email: true,
      },
      phone: {
        required: true,
        number: true,
      },
      password: {
        required: true,
        minlength: 5,
      },
      confirm_password: {
        required: true,
        equalTo: '#password',
      },
      role_id: 'required',
    },
    messages: {
      user_pic: {
        extension: 'Please select only image',
      },
      user_first_name: 'Please enter first name',
      user_last_name: 'Please enter last name',
      email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
      phone: {
        required: 'Please enter mobile number',
        number: 'Please enter valid number',
      },
      password: {
        required: 'Please provide a password',
        minlength: 'Your password must be at least 5 characters long',
      },
      confirm_password: {
        required: 'Please provide confirm password',
        equalTo: 'Password did not match',
      },
      role_id: 'Please select role',
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // ADD CUSTOMER FORM
  $("form[name='addcustomer']").validate({
    // Specify validation rules
    rules: {
      first_name: 'required',
      last_name: 'required',
      email: {
        //required : false,
        email: true,
      },
      phone: {
        //required : false,
        number: true,
      },
      home_phone: {
        number: true,
      },
      work_phone: {
        number: true,
      },
      billing_street: 'required',
      //billing_street_2 : "required",
      billing_city: 'required',
      billing_state: 'required',
      billing_zipcode: {
        required: true,
        //number: true,        
        validationUSzipcodeCApostcode: true,
      },
      customer_status: 'required',
      //assign_property : "required",
    },
    messages: {
      first_name: 'Please enter first name',
      last_name: 'Please enter last name',
      email: {
        //required : "Please provide email address",
        email: 'Please enter a valid email address',
      },
      phone: {
        //required : "Please provide a phone number",
        number: 'Please enter valid number',
      },
      home_phone: {
        number: 'Please enter valid number',
      },
      work_phone: {
        number: 'Please enter valid number',
      },
      billing_street: 'Please enter address',
      //billing_street_2 : "Please enter address2",
      billing_city: 'Please enter city',
      billing_state: 'Please enter state',
      billing_zipcode: {
        required: 'Please enter zipcode',
        number: 'Please enter valid number',
      },
      customer_status: 'Please select customer status',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_customer').modal('hide');
            customerList();
            swal('Customer !', 'Added Successfully ', 'success');
            if ($(form).hasClass('redirection')) {
              setInterval(function () {
                location.reload();
              }, 2000);
            }
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });
  // CUSTOMER LOGIN FORM
  $("form[name='customerslogin']").validate({
    // Specify validation rules
    rules: {
      email: {
        required: true,
        email: true,
      },
      password: {
        required: true,
        minlength: 5,
      },
    },
    messages: {
      password: {
        required: 'Please provide a password',
        minlength: 'Password must be at least 5 characters long',
      },
      email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // ADD PROGRAM FORM
  $("form[name='addprogram']").validate({
    // Specify validation rules
    rules: {
      program_name: 'required',
      program_price: 'required',
      // program_job : "required",
      // program_notes : "required"
    },
    messages: {
      program_name: 'Please enter program name',
      program_price: 'Please select pricing',
      // program_job : "Please select job",
      // program_notes : "Please enter program note",
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_program').modal('hide');
            programPriceOverRide = $('#assign_program_ids2').val();
            if (programPriceOverRide) {
              programList(programPriceOverRide);
              setTimeout(function () {
                reintlizeMultiselectprogramPriceOver()();
              }, 3000);
            } else {
              programList();
            }
            $('#loading').css('display', 'none');
            swal('Program !', 'Added Successfully ', 'success');
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // ADD PROPERTY FORM
  var $prospect = $('select[id="property_status"]')
  $("form[name='addproperty']").validate({
    // Specify validation rules
    rules: {
      property_title: 'required',
      property_address: 'required',
      property_city: 'required',
      property_state: 'required',
      property_zip: {
        required: true,
        //number: true,
        validationUSzipcodeCApostcode: true,
      },
      property_type: 'required',
      yard_square_feet: {
        required: function (){
          return $prospect.val() != 2;
        },
        number: true,
      },
      assign_customer: 'required',
      property_price: {
        number: true,
      },
      property_status: 'required',
      source: 'required',
    },
    messages: {
      property_title: 'Please enter property name',
      property_address: 'Please enter address',
      property_city: 'Please enter city',
      property_state: 'Please enter state',
      property_zip: {
        required: 'Please enter zipcode',
        number: 'Please enter valid number',
      },
      property_type: 'Please enter type',
      yard_square_feet: {
        required: 'Please enter yard area',
        number: 'Please enter valid number',
      },
      assign_customer: 'Please select customer',
      property_price: {
        number: 'Please enter valid number',
      },
      property_status: 'Please select property status',
      source: 'Please select source status',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          dataType: 'json',
          success: function (response) {
            if (response.status == 200) {
              $('#loading').css('display', 'none');
              form.reset();
              $('.summernote_property').summernote('reset');
              $('#modal_add_property').modal('hide');
              var customer_id = $('#customer_id').val();
              var multipleCutomerId = $('#multipleCutomerId').val();
              proertyPriceOverRide = $('#assign_property_ids2').val();
              selectedProperties = $('#property_list').val();
              // alert(selectedProperties);
              if (customer_id) {
                // uodate customer
                if (selectedProperties) {
                  propertyList('', selectedProperties, response.result);
                } else {
                  propertyList('', '', response.result);
                }
                // propertyListSelectedByCustomer(customer_id);
                if (multipleCutomerId) {
                  redirectLink =
                      window.location.origin +
                      '/admin/editCustomer/' +
                      customer_id +
                      '/1';
                  window.location.href = redirectLink;
                }
              } else if (proertyPriceOverRide) {
                //  add program
                propertyList(proertyPriceOverRide);
                setTimeout(function () {
                  reintlizeMultiselectpropertyPriceOver()();
                }, 3000);
              } else if (selectedProperties) {
                propertyList('', selectedProperties, response.result);
              } else {
                propertyList('', '', response.result);
              }
              swal('Property !', 'Added Successfully ', 'success');
              if ($(form).hasClass('redirection')) {
                setInterval(function () {
                  location.reload();
                }, 2000);
              }
            } else {
              swal({
                type: 'error',
                title: 'Oops...',
                text: response.msg,
              });
            }
          },
        });
      } else {
        form.submit();
      }
    },
  });
  // ADD PROPERTY FORM
  var $prospect = $('select[id="property_status_modal"]')
  $("form[name='addproperty_modal']").validate({
    // Specify validation rules
    rules: {
      property_title: 'required',
      property_address: 'required',
      //property_address_2: "required",
      property_city: 'required',
      property_state: 'required',
      property_zip: {
        required: true,
        number: true,
      },

      // property_area: "required",
      property_type: 'required',
      yard_square_feet: {
        required: function (){
          return $prospect.val() != 1;
        },
        number: true,
      },
      // assign_program: "required",
      assign_customer: 'required',
      property_price: {
        number: true,
      },
      property_status: 'required',
      // source_modal: {
      //   required: function () {
      //     return $prospect.val() == 1;
      //   }
      // },
      source_modal: 'required',
    },
    messages: {
      property_title: 'Please enter property name',
      property_address: 'Please enter address',
      //property_address_2: "Please enter address2",
      property_city: 'Please enter city',
      property_state: 'Please enter state',
      property_zip: {
        required: 'Please enter zipcode',
        number: 'Please enter valid number',
      },
      // property_area: "Please select service area",
      property_type: 'Please enter type',
      yard_square_feet: {
        required: 'Please enter yard area',
        number: 'Please enter valid number',
      },
      // assign_program: "Please select program",
      assign_customer: 'Please select customer',
      property_price: {
        number: 'Please enter valid number',
      },
      property_status: 'Please select property status',
      source_modal: 'Please select source status',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          dataType: 'json',
          success: function (response) {
            if (response.status == 200) {
              $('#loading').css('display', 'none');
              form.reset();
              $('.summernote_property').summernote('reset');
              $('#modal_add_property').modal('hide');
              var customer_id = $('#customer_id').val();
              var multipleCutomerId = $('#multipleCutomerId').val();
              proertyPriceOverRide = $('#assign_property_ids2').val();
              selectedProperties = $('#property_list').val();
              // alert(selectedProperties);
              if (customer_id) {
                // uodate customer
                if (selectedProperties) {
                  propertyList('', selectedProperties, response.result);
                } else {
                  propertyList('', '', response.result);
                }
                // propertyListSelectedByCustomer(customer_id);
                if (multipleCutomerId) {
                  redirectLink =
                      window.location.origin +
                      '/admin/editCustomer/' +
                      customer_id +
                      '/1';
                  window.location.href = redirectLink;
                }
              } else if (proertyPriceOverRide) {
                //  add program
                propertyList(proertyPriceOverRide);
                setTimeout(function () {
                  reintlizeMultiselectpropertyPriceOver()();
                }, 3000);
              } else if (selectedProperties) {
                propertyList('', selectedProperties, response.result);
              } else {
                propertyList('', '', response.result);
              }
              swal('Property !', 'Added Successfully ', 'success');
              if ($(form).hasClass('redirection')) {
                setInterval(function () {
                  location.reload();
                }, 2000);
              }
            } else {
              swal({
                type: 'error',
                title: 'Oops...',
                text: response.msg,
              });
            }
          },
        });
      } else {
        form.submit();
      }
    },
  });
  // ADD PRODUCT FORM
  $("form[name='addproduct']").validate({
    // Specify validation rules
    rules: {
      product_name: 'required',
      // epa_reg_nunber: "number",
      product_cost: {
        required: true,
        number: true,
      },
      // product_cost_per: "required",
      formulation: {
        // required : true,
        number: true,
      },
      // formulation_per: {
      //    required : true,
      //   number :true
      // },
      // formulation_per_unit: "required",
      max_wind_speed: {
        // required : true,
        number: true,
      },
      application_rate: {
        // required : true,
        number: true,
      },
      application_per: 'required',
      temperature_information: {
        // required : true,
        number: true,
      },

      temperature_unit: 'required',

      //  "active_ingredient[]": "required",

      'percent_active_ingredient[]': {
        // required :  true,
        number: true,
      },/*,'area_of_property_treated[]': {
         required :  false,
        //number: true,
      },*/
    },
    // ignore: ':hidden:not("#area_of_property_treated_list")',
    errorPlacement: function (error, element) {
      if (element.attr('name') == 'percent_active_ingredient[]' /*|| element.attr('name') == 'area_of_property_treated[]'*/) {
        error.appendTo(element.parent('div').parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },

    messages: {
      product_name: 'Please enter product name',
      // epa_reg_nunber: "Please enter valid number",
      product_cost: {
        required: 'Please enter product cost',
        number: 'Please enter valid number',
      },
      // product_cost_per : "Please enter product cost Per",
      formulation: {
        // required : "Please enter formulation",
        number: 'Please enter valid number',
      },
      // formulation_per : {
      //   required : "Please enter formulation per",
      //   number : "Please enter valid number"
      // },
      // formulation_per_unit : "Please enter formulation unit",
      max_wind_speed: {
        // required : "Please enter wind speed",
        number: 'Please enter valid number',
      },
      application_rate: {
        // required : "Please enter application rate",
        number: 'Please enter valid number',
      },
      application_rate_per: {
        // required :"Please enter application rate per",
        number: 'Please enter valid number',
      },
      temperature_information: {
        // required : "Please enter temperature information",
        number: 'Please enter valid number',
      },
      temperature_unit: 'Please enter temperature unit',

      // "active_ingredient[]" : "Please enter active ingredient",

      'percent_active_ingredient[]': {
        required : "Please enter  % of active ingredient",
        number: 'Please enter valid % of active ingredient',
      },
      //'area_of_property_treated[]': {
      //required : 'Please enter area of property to be treated',
      //number: 'Please enter valid % of active ingredient',
      //},
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission

        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_product').modal('hide');
            productList();
            swal('Product !', 'Added Successfully ', 'success');
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },

  });


  // ADD JOB FORM
  $("form[name='addjob']").validate({
    // Specify validation rules
    rules: {
      job_name: 'required',
      job_price: {
        required: true,
        number: true,
      },
    },
    errorPlacement: function (error, element) {
      if (element.attr('name') == 'job_price') {
        error.appendTo(element.parent('div').parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    messages: {
      job_name: 'Please enter service name',
      job_price: {
        required: 'Please enter service price',
        number: 'Please enter valid service price',
      },
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission

        $('#loading').css('display', 'block');

        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#loading').css('display', 'none');

            $('#modal_add_job').modal('hide');
            viewJobList();
            swal('Service !', 'Added Successfully ', 'success');
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // ADD SECONDARY EMAIL FORM
  $("form[name='add_secondary_email']").validate({
    // Specify validation rules
    rules: {
      secondary_email: {
        required: true,
        email: true,
      },
    },
    messages: {
      secondary_email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        var already_added_emails = $('#secondary_email_list_hid').val();
        form_data = `${form_data}&already_added_emails=${already_added_emails}`;
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          dataType: 'json',
          success: function (response) {
            if (response.status == 200) {
              $('#secondary_email_list_hid').val(response.result);
              $('#secondary_email_list').html(response.result);
              $('#loading').css('display', 'none');
              form.reset();
              $('#modal_add_secondary_emails').modal('hide');
              $('#reset_secondary_email_link').removeClass('hidden');
              $('#add_secondary_email_link i').removeClass('pt-5');
              swal('Email !', 'Added Successfully ', 'success');
            } else {
              swal({
                type: 'error',
                title: 'Oops...',
                text: response.msg,
              });
            }
          },
        });
      } else {
        form.submit();
      }
    },
  });

  // TECHNICIAN JOB ASSIGN FORM
  $("form[name='tecnicianjobassign']").validate({
    rules: {
      technician_id: 'required',
      job_assign_date: 'required',
      route_select: 'required',
      route_input: 'required',
      specific_time: {
        required: true,
        checking: '#jobAssignDate',
      },
    },
    messages: {
      technician_id: 'Please select technician',
      job_assign_date: 'Please select date',
      route_select: 'Please select any route',
      route_input: 'Please enter route name',
      specific_time: {
        required: 'Please select specific time',
        checking: 'Please do not select past time',
      },
    },
    errorPlacement: function (error, element) {
      if (
          element.attr('name') == 'route_select' ||
          element.attr('name') == 'route_input'
      ) {
        error.appendTo('.route_error');
      } else if (element.attr('name') == 'specific_time') {
        error.appendTo(element.parent().parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    submitHandler: function (form) {
      var group_id = [];
      $('input:checkbox[name=group_id]:checked').each(function () {
        group_id.push($(this).val());
      });
      $('#group_id').val(group_id);

      //$('#loading').css('display', 'block'); //commented this out

      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission
      // alert(form_data);
      $('#job_assign_bt').prop('disabled', true);

      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        dataType: 'JSON',
        success: function (response) {
          $('#loading').css('display', 'none');

          form.reset();
          $('#modal_theme_primary').modal('hide');
          $('#job_assign_bt').prop('disabled', false);

          if (response.status == 200) {
            swal({
              title: response.msg,
            }).then((value) => {
              swal({
                title: 'Do you want to print route now?',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#009402',
                cancelButtonColor: '#FFBE2C',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
              })
                  .then(function (result) {
                    if (result.value) {
                      //swal("Printed", "Printing Routes", "success");
                      var href =
                          '/admin/invoices/printTechWorksheet/' +
                          response.technician_assigned;
                      var win = window.open(href, '_blank');
                      win.focus();
                      console.log('good');
                    } else if (result.dismiss == 'cancel') {
                      console.log('cancel');
                    }
                  })
                  .then(function () {
                    location.reload();
                  });
            });
          } else if (response.status == 400) {
            swal({
              type: 'error',
              title: 'Oops...',
              text: response.msg,
            });
          } else {
            swal({
              type: 'error',
              title: 'Oops...',
              text: 'Something went wrong!',
            });
          }
        },
      });
    },
  });
  // EDIT TECHNICIAN JOB ASSIGN FORM
  $("form[name='tecnicianjobassignedit']").validate({
    rules: {
      technician_id: 'required',
      job_assign_date: {
        required: true,
        dateChecking: true,
      },
      route_select: 'required',
      route_input: 'required',
      specific_time: {
        required: true,
        checking: '#jobAssignDateEdit',
      },
    },
    messages: {
      technician_id: 'Please select technician',
      job_assign_date: {
        required: 'Please select date',
        dateChecking: 'Please Do not select past date',
      },
      route_select: 'Please select any route',
      route_input: 'Please enter route name',
      specific_time: {
        required: 'Please select specific time',
        checking: 'Please do not select past time',
      },
    },
    errorPlacement: function (error, element) {
      if (
          element.attr('name') == 'route_select' ||
          element.attr('name') == 'route_input'
      ) {
        error.appendTo('.route_edit_error');
      } else if (element.attr('name') == 'specific_time') {
        error.appendTo(element.parent().parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    submitHandler: function (form) {
      form.submit();
    },
  });

  // EDIT TECHNICIAN JOB ASSIGN CALENDAR FORM
  $("form[name='dropEventForm']").validate({
    rules: {
      route_select: 'required',
      route_input: 'required',
      specific_time: {
        required: true,
        checking: '#job_assign_date_drop',
      },
    },
    messages: {
      route_select: 'Please select any route',
      route_input: 'Please enter route name',
      specific_time: {
        required: 'Please select specific time',
        checking: 'Please do not select past time',
      },
    },
    errorPlacement: function (error, element) {
      if (
          element.attr('name') == 'route_select' ||
          element.attr('name') == 'route_input'
      ) {
        error.appendTo('.route_drop_edit_error');
      } else if (element.attr('name') == 'specific_time') {
        error.appendTo(element.parent().parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    submitHandler: function (form) {
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission

      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        dataType: 'json',
      }).done(function (data) {
        if (data.status == 200) {
          checkModalSituation = 2;
          form.reset();
          $('#modal_drop_event').modal('hide');
          rearraangedropModalForm();
        } else if (data.status == 400) {
          checkModalSituation = 1;
          swal({
            type: 'error',
            title: 'Oops...',
            text: data.msg,
          });
        } else {
          checkModalSituation = 1;
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          });
        }
      });
    },
  });
  // EDIT MULTIPLE TECHNICIAN JOB ASSIGN FORM
  $("form[name='tecnicianjobassignmultipleedit']").validate({
    rules: {
      technician_id: 'required',
      job_assign_date: 'required',
      route_select: 'required',
      route_input: 'required',
      specific_time: {
        required: true,
        checking: '#jobAssignDateEditMultiple',
      },
    },
    messages: {
      technician_id: 'Please select technician',
      job_assign_date: 'Please select date',
      route_select: 'Please select any route',
      route_input: 'Please enter route name',
      specific_time: {
        required: 'Please select specific time',
        checking: 'Please do not select past time',
      },
    },
    errorPlacement: function (error, element) {
      if (
          element.attr('name') == 'route_select' ||
          element.attr('name') == 'route_input'
      ) {
        error.appendTo('.route_edit_multiple_error');
      } else if (element.attr('name') == 'specific_time') {
        error.appendTo(element.parent().parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    submitHandler: function (form) {
      $('#loading').css('display', 'block');

      var selectcheckbox = [];
      $('input:checkbox[name=selectcheckbox]:checked').each(function () {
        selectcheckbox.push($(this).attr('technician_job_assign_ids'));
      });

      $('#multiple_technician_job_assign_id').val(selectcheckbox);

      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission

      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        dataType: 'json',
      }).done(function (data) {
        // alert(data);
        $('#loading').css('display', 'none');

        if (data.status == 200) {
          swal('Scheduled Services !', 'Updated Successfully ', 'success').then(
              function () {
                location.reload();
              }
          );
        } else if (data.status == 400) {
          swal({
            type: 'error',
            title: 'Oops...',
            text: data.msg,
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!',
          });
        }
      });
      // form.submit();
    },
  });
  // CUSTOMER EMAIL FORM
  $("form[name='customeremail']").validate({
    rules: {
      email: {
        required: true,
        email: true,
      },
      message: {
        required: true,
        noSpace: true,
      },
    },
    messages: {
      email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
      message: {
        required: 'Please enter message',
        noSpace: 'Please enter  valid  message',
      },
    },
    submitHandler: function (form) {
      $('#sendmsgbt').prop('disabled', true);
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission
      //alert(form_data);
      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        success: function (response) {
          form.reset();
          $('#sendmsgbt').prop('disabled', false);
          swal('Message !', 'Sent Successfully ', 'success');
        },
      });
    },
  });
  // COMPLETE JOB FORM
  $("form[name='completejobform']").validate({
    errorPlacement: function (error, element) {
      error.appendTo(element.parent('div').parent('div'));
    },
    submitHandler: function (form) {
      $('#modal_mixture_application').modal('toggle');
      var is_group_billing = $('#is_group_billing').val();
      var basys_autocharge = $('#basys_autocharge').val();
      var clover_autocharge = $('#clover_autocharge').val();
      var customerEmail = $('#customer_email').val();

      var email = false;
      var selected;
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission

      var program_price = document.getElementById('prog_price').value;
      if(program_price != 3 && basys_autocharge != 1 && customerEmail == 1 && clover_autocharge != 1 && is_group_billing !=1) {
        //New Invoice Popup
        console.log('New Invoice Popup');
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
                /**   * 0: email
                      * 1: print **/

                if(selected[0] && selected[1]){
                  var url_email = '';
                  if(  data.statement_url !== undefined && data.statement_url !== '') {
                    //console.log('statement_url');
                    url_email = data.statement_url;
                  } else if ( data.email_url !== undefined && data.email_url !== '') {
                    //console.log('email_url');
                    url_email = data.email_url;
                  }

                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:'<p>You will be redirected back to the dashboard after proceeding.</p><a href="'+ data.inv_url + '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>'
                  }).then((result) => {
                    // window.location = data.url;
                    window.location = data.url;
                  })

                  $.ajax({
                    type: request_method,
                    url: url_email,
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                      //console.log('email sent data response1: '+data)
                    }
                  });
                  function redirect(){
                    setTimeout(function(){
                      window.location = data.url;
                    }, 3000);
                  }



                  var btn = document.getElementById("myBtn"); btn.addEventListener("click", redirect);
                  // window.location = data.email_url;

                } else if(selected[0]) {
                  var url_email = '';
                  if(  data.statement_url !== undefined && data.statement_url !== '') {
                    //console.log('statement_url');
                    url_email = data.statement_url;
                  } else if ( data.email_url !== undefined && data.email_url !== '') {
                    //console.log('email_url');
                    url_email = data.email_url;
                  }

                    $.ajax({
                      type: request_method,
                      url: url_email,
                      //data: form_data,
                      cache: false,
                      dataType: 'json',
                      success: function (data) {
                        //console.log('email sent data response2: '+data)
                        //console.log(data)
                        //setTimeout(function(){

                        //}, 3000);
                      }});
                    function redirect(){
                      setTimeout(function(){
                        window.location = data.url;
                      }, 3000);
                    }
                    redirect();
                    //window.location = data.url;


                } else if(selected[1]) {

                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:'<p>You will be redirected back to the dashboard after proceeding.</p><a href="'+ data.inv_url + '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>'
                  })

                  function redirect(){
                    setTimeout(function(){
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById("myBtn"); btn.addEventListener("click", redirect);
                } else {

                  function redirect(){
                    setTimeout(function(){
                      window.location = data.url;
                    }, 3000);
                  }
                  redirect();
                }

              } else if (data.status==400) {

                window.location = data.url;
              }
            }
          });
        })

      } else if(program_price != 3 && basys_autocharge != 1 && clover_autocharge != 1 && is_group_billing !=1) {
        //Old Invoice Popup
        console.log('Old Invoice Popup');
        Swal.fire({
          title: 'Invoice',
          text: 'Do you want to Print an invoice now?',
          html: '<p>Would you like to print an invoice?</p><div class="form-check"><input class="form-check-input" type="checkbox" value="2" id="print"><label class="form-check-label" for="print">&nbsp;&nbsp;Print</label></div>',
          preConfirm: () => {
            selected = [
              document.getElementById('print').checked,
            ];

            return [
              document.getElementById('print').checked,
            ];
          },
        }).then((result) => {
          $('#loading').css('display', 'block');

          $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            cache: false,
            dataType: 'json',
            success: function (data) {
              $('#loading').css('display', 'none');

              if (data.status == 200) {
                /**
                 * 0: email
                 * 1: print
                 **/
                if (selected[0]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:
                        '<p>You will be redirected back to the dashboard after proceeding.</p><a href="' +
                        data.inv_url +
                        '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>',
                  }); //.then((result) => {
                  //                                        window.location = data.email_url;
                  //                                    })
                  function redirect() {
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById('myBtn');
                  btn.addEventListener('click', redirect);
                  // window.location = data.email_url;
                } else if (selected[0]) {
                  // console.log(data.invoice_id_nums);
                  // console.log(data.sent_status)

                  // bulkStatusChange(data.invoice_id_nums, 1); //sent status 1: Sent
                  if(data.statement_url){
                    window.open(data.statement_url);
                  }
                  window.open(data.email_url);

                } else if (selected[1]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:
                        '<p>You will be redirected back to the dashboard after proceeding.</p><a href="' +
                        data.inv_url +
                        '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>',
                  });
                  function redirect() {
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById('myBtn');
                  btn.addEventListener('click', redirect);
                } else {
                  window.location = data.url;
                }
              } else if (data.status == 400) {
                window.location = data.url;
              }
            },
          });
        });
      }else if(program_price != 3 && customerEmail == 1 && is_group_billing !=1) {
        // console.log("Customer Email: " + customerEmail);
        //New Invoice Popup
        console.log('New Invoice Popup 2');
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
            type: 'POST',
            url: post_url,
            data: form_data,
            cache: false,
            dataType: "json",
            beforeSend: function(){
              console.log('Inside Ajax Call but not success');
            },
            success: function (data){

              console.log('Inside Success function');
              $("#loading").css("display","none");

              if (data.status==200) {
                /**
                 * 0: email
                 * 1: print
                 **/
                if(selected[0] && selected[1]){
                  if(data.email_url !== '') {
                    $.ajax({
                      type: request_method,
                      url: data.email_url,
                      cache: false,
                      dataType: 'json',
                      success: function (data) {
                        console.log(data)

                      }
                    });
                  }

                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:'<p>You will be redirected back to the dashboard after proceeding.</p><a href="'+ data.inv_url + '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>'
                  }).then((result) => {
                    window.location = data.url;
                  })
                  function redirect(){
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }


                  var btn = document.getElementById("myBtn"); btn.addEventListener("click", redirect);
                  // window.location = data.email_url;

                } else if(selected[0]) {
                  if(data.statement_url !== ''){

                    $.ajax({
                      type: request_method,
                      url: data.email_url,
                      //data: form_data,
                      cache: false,
                      dataType: 'json',
                      success: function (data) {
                        console.log(data)
                        //setTimeout(function(){

                        //}, 3000);
                      }});
                    function redirect(){
                      setTimeout(function(){
                        window.location = data.url;
                      }, 3000);
                    }
                    redirect();
                    //window.location = data.url;
                  }

                } else if(selected[1]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:'<p>You will be redirected back to the dashboard after proceeding.</p><a href="'+ data.inv_url + '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>'
                  })
                  function redirect(){
                    setTimeout(function(){
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById("myBtn"); btn.addEventListener("click", redirect);
                } else {
                  window.location = data.url;
                }

              } else if (data.status==400) {

                window.location = data.url;

              }

            },
            error: function(xhr, textStatus, error){
              console.log(xhr.statusText);
              console.log(textStatus);
              console.log(error);
            }
          });

        })

      } else if (program_price != 3 && basys_autocharge != 1 && clover_autocharge != 1) {
        //Old Invoice Popup
        console.log('Old Invoice Popup 2');
        Swal.fire({
          title: 'Invoice',
          text: 'Do you want to Print an invoice now?',
          html: '<p>Would you like to print an invoice?</p><div class="form-check"><input class="form-check-input" type="checkbox" value="2" id="print"><label class="form-check-label" for="print">&nbsp;&nbsp;Print</label></div>',
          preConfirm: () => {
            selected = [
              document.getElementById('print').checked,
            ];

            return [
              document.getElementById('print').checked,
            ];
          },
        }).then((result) => {
          console.log(result);
          $('#loading').css('display', 'block');

          $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            cache: false,
            dataType: 'json',
            success: function (data) {
              $('#loading').css('display', 'none');

              if (data.status == 200) {
                /**
                 * 0: email
                 * 1: print
                 **/
                if (selected[0]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:
                        '<p>You will be redirected back to the dashboard after proceeding.</p><a href="' +
                        data.inv_url +
                        '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>',
                  }); //.then((result) => {
                  //                                        window.location = data.email_url;
                  //                                    })
                  function redirect() {
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById('myBtn');
                  btn.addEventListener('click', redirect);
                  // window.location = data.email_url;
                } else if (selected[0]) {
                  // console.log(data.invoice_id_nums);
                  // console.log(data.sent_status)

                  // bulkStatusChange(data.invoice_id_nums, 1); //sent status 1: Sent
                  if(data.statement_url !== ''){
                    // Opens the Invoice email sending function asyncronously
                    //before opening the work statement email sending function
                    async function invoice_window(){
                      var wind = window.open(data.email_url, "New Window");
                      wind.close();
                    }
                    invoice_window().then(() => {
                      window.location = data.statement_url;
                    });
                  } else {
                    window.location = data.email_url;
                  }
                } else if (selected[1]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:
                        '<p>You will be redirected back to the dashboard after proceeding.</p><a href="' +
                        data.inv_url +
                        '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>',
                  });
                  function redirect() {
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById('myBtn');
                  btn.addEventListener('click', redirect);
                } else {
                  window.location = data.url;
                }
              } else if (data.status == 400) {
                window.location = data.url;
              }
            },
          });
        });
      }  else if (program_price != 3 && clover_autocharge != 1) {
        //Old Invoice Popup
        console.log('Old Invoice Popup 3');
        Swal.fire({
          title: 'Invoice',
          text: 'Do you want to Print an invoice now?',
          html: '<p>Would you like to print an invoice?</p><div class="form-check"><input class="form-check-input" type="checkbox" value="2" id="print"><label class="form-check-label" for="print">&nbsp;&nbsp;Print</label></div>',
          preConfirm: () => {
            selected = [
              document.getElementById('print').checked,
            ];

            return [
              document.getElementById('print').checked,
            ];
          },
        }).then((result) => {
          console.log(JSON.stringify(result));
          $('#loading').css('display', 'block');

          $.ajax({
            type: request_method,
            url: post_url,
            data: form_data,
            cache: false,
            dataType: 'json',
            success: function (data) {
              $('#loading').css('display', 'none');

              if (data.status == 200) {
                /**
                 * 0: email
                 * 1: print
                 **/
                if (selected[0]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:
                        '<p>You will be redirected back to the dashboard after proceeding.</p><a href="' +
                        data.inv_url +
                        '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>',
                  }); //.then((result) => {
                  //                                        window.location = data.email_url;
                  //                                    })
                  function redirect() {
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById('myBtn');
                  btn.addEventListener('click', redirect);
                  // window.location = data.email_url;
                } else if (selected[0]) {
                  // console.log(data.invoice_id_nums);
                  // console.log(data.sent_status)

                  // bulkStatusChange(data.invoice_id_nums, 1); //sent status 1: Sent
                  if(data.statement_url !== ''){
                    // Opens the Invoice email sending function asyncronously
                    //before opening the work statement email sending function
                    async function invoice_window(){
                      var wind = window.open(data.email_url, "New Window");
                      wind.close();
                    }
                    invoice_window().then(() => {
                      window.location = data.statement_url;
                    });
                  } else {
                    window.location = data.email_url;
                  }
                } else if (selected[1]) {
                  Swal.fire({
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    html:
                        '<p>You will be redirected back to the dashboard after proceeding.</p><a href="' +
                        data.inv_url +
                        '" target="_blank"><button class="swal2-styled" id="myBtn" style="background-color: #4CAF50 !important; color: white !important; border-radius: 8px !important; text-align: center !important; vertical-align: middle !important;">Click to Print</button></a>',
                  });
                  function redirect() {
                    setTimeout(function () {
                      window.location = data.url;
                    }, 3000);
                  }
                  var btn = document.getElementById('myBtn');
                  btn.addEventListener('click', redirect);
                } else {
                  window.location = data.url;
                }
              } else if (data.status == 400) {
                window.location = data.url;
              }
            },
          });
        });
      } else {
        console.log('Default');
        $('#loading').css('display', 'block');

        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          dataType: 'json',
          success: function (data) {
            $('#loading').css('display', 'none');

            console.log(data);

            // alert(data.status);
            //alert(result.data);
            if (data.status == 200) {
              if (result.value) {
                window.open(data.inv_url);
                window.location = data.url;
              } else {
                window.location = data.url;
              }
            } else if (data.status == 400) {
              window.location = data.url;
            }
          },
        });
      }
    },
  });
  // COMPLETE JOB Sales VisitFORM
  $("form[name='completejobformsales']").validate({
    errorPlacement: function (error, element) {
      error.appendTo(element.parent('div').parent('div'));
    },
    submitHandler: function (form) {
      $('#modal_sales_visit').modal('toggle');


      var email = false;

      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission


      $('#loading').css('display', 'block');

      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        dataType: 'json',
        success: function (data) {
          $('#loading').css('display', 'none');

          console.log(data);

          // alert(data.status);
          //alert(result.data);
          if (data.status == 200) {
            if (result.value) {
              window.open(data.inv_url);
              window.location = data.url;
            } else {
              window.location = data.url;
            }
          } else if (data.status == 400) {
            window.location = data.url;
          }
        },
      });
      // }
    },
  });
  // ADD INVOICE FORM
  $("form[name='addinvoice']").validate({
    rules: {
      customer_id: 'required',
      property_id: 'required',
      invoice_date: 'required',
      cost: {
        required: true,
        number: true,
        greaterThanEqualMethod: 0,
      },
      program_id: 'required',
      partial_payment: {
        number: true,
        greaterThan: 0,
        lessThanEqualMethod: '#over_all_total',
      },
    },
    errorPlacement: function (error, element) {
      if (
          element.attr('name') == 'cost' ||
          element.attr('name') == 'over_all_total' ||
          element.attr('name') == 'partial_payment' ||
          element.attr('name') == 'balance_due'
      ) {
        error.appendTo(element.parent('div').parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    messages: {
      customer_id: 'Please select customer',
      customer_email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
      property_id: 'Please select property address',
      invoice_date: 'Please select invoice date',
      cost: {
        required: 'Please enter cost',
        number: 'Please enter valid number',
        greaterThanEqualMethod: 'Please enter positive value',
      },
      program_id: 'Please select program',
      partial_payment: {
        number: 'Please enter valid number',
        greaterThan: 'Please enter a value greater than 0',
        lessThanEqualMethod:
            'Value should be less than or equal to total cost value',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // ADD ESTIMATE FORM
  var $prospect = $('select[id="prospect_status"]')
  $("form[name='addestimate']").validate({
    rules: {
      customer_id: 'required',
      property_id: 'required',
      estimate_date: 'required',
      program_price: 'required',
      source: {
        required: function () {
          return $prospect.val() == 1;
        }
      },
    },
    errorPlacement: function (error, element) {
      error.appendTo(element.parent('div'));
    },
    messages: {
      customer_id: 'Please select customer',
      property_id: 'Please select property address',
      estimate_date: 'Please select estimate date',
      invoice_date: 'Please select invoice date',
      cost: {
        required: 'Please enter cost',
        number: 'Please enter valid number',
        greaterThan: 'Please enter a value greater than 0',
      },
      program_price: 'Please select pricing',
      partial_payment: {
        number: 'Please enter valid number',
        greaterThan: 'Please enter a value greater than 0',
        lessThanEqualMethod:
            'Value should be less than or equal to total cost value',
      },
      source: 'Please select source status',
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // ESTIMATE SETTINGS FORM
  $("form[name='estimatesetting']").validate({
    submitHandler: function (form) {
      form.submit();
    },
  });
  // COMPANY DETAILS FORM
  $("form[name='companydetails']").validate({
    // Specify validation rules
    rules: {
      company_name: 'required',
      company_address: 'required',
      company_phone_number: {
        required: true,
        phoneUS: true,
      },
      company_email: {
        required: true,
        email: true,
      },
      web_address: {
        // required  : true,
        url: true,
      },
      invoice_color: 'required',
      default_display_length: {
        required: true,
        number: true,
        min: 1,
      },
    },
    messages: {
      company_name: 'Please enter company name',
      company_address: 'Please enter company address',
      company_phone_number: {
        required: 'Please enter mobile number',
        phoneUS: 'Please enter valid number',
      },
      company_email: {
        required: 'Please provide email address',
        email: 'Please enter a valid email address',
      },
      web_address: {
        // required  : "Please enter web address",
        url: 'Please enter a valid url',
      },
      invoice_color: 'Please select invoice color',
      default_display_length: {
        required: 'Please enter company name default # of entries',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });

  // START QUICKBOOKS
  var OAuthCode = function (url) {
    this.loginPopup = function (parameter) {
      this.loginPopupUri(parameter);
    };
    this.loginPopupUri = function (parameter) {
      // Launch Popup
      var parameters = 'location=1,width=800,height=650';
      parameters +=
          ',left=' +
          (screen.width - 800) / 2 +
          ',top=' +
          (screen.height - 650) / 2;
      var win = window.open(url, 'connectPopup', parameters);
      var pollOAuth = window.setInterval(function () {
        try {
          if (win.document.URL.indexOf('code') != -1) {
            window.clearInterval(pollOAuth);
            win.close();
            location.reload();
          }
        } catch (e) {
          console.log(e);
        }
      }, 100);
    };
  };
  // QUICKBOOKS AUTH FORM
  $("form[name='quickbookauth']").validate({
    // Specify validation rules
    rules: {
      quickbook_client_id: 'required',
      quickbook_client_secret: 'required',
    },
    messages: {
      quickbook_client_id: 'Please enter client id',
      quickbook_client_secret: 'Please enter client secret',
    },
    submitHandler: function (form) {
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
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
        },
      });
    },
  });
  // SETTINGS FORM
  $("form[name='settings']").validate({
    // Specify validation rules
    rules: {
      start_location: 'required',
      end_location: 'required',
    },
    messages: {
      start_location: 'Please enter start location',
      end_location: 'Please enter end location',
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // INVOICE DETAILS FORM
  $("form[name='invoicedetails']").validate({
    // Specify validation rules
    rules: {
      payment_terms: 'required',
      pay_now_btn_link: {
        required: true,
        url: true,
      },
      convenience_fee: {
        number: true,
      },
    },
    errorPlacement: function (error, element) {
      if (element.attr('name') == 'convenience_fee') {
        error.appendTo(element.parent('div').parent('div'));
      } else {
        error.appendTo(element.parent('div'));
      }
    },
    messages: {
      payment_terms: 'Please select any payment terms',
      pay_now_btn_link: {
        required: 'Please enter your pay link here',
        url: 'Please enter a valid URL',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // BASYS INTEGRATION FORM
  $("form[name='basysintrigation']").validate({
    // Specify validation rules
    rules: {
      api_key: 'required',
    },
    messages: {
      api_key: 'Basys api key is required',
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // ADD SERVICE AREA FORM
  $("form[name='addservicearea']").validate({
    rules: {
      category_area_name: 'required',
    },
    messages: {
      category_area_name: 'Please enter service area name',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_service_area').modal('hide');
            $('#loading').css('display', 'none');
            swal('Service Area', 'Added Successfully ', 'success');
            getServiceAreaOption();
            getServiceAreaList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // ADD SALES TAX AREA FORM
  $("form[name='addsalestexarea']").validate({
    rules: {
      tax_name: 'required',
      tax_value: {
        required: true,
        number: true,
        min: 0,
      },
    },
    messages: {
      tax_name: 'Please enter sales tax area name',
      tax_value: {
        required: 'Please enter sales tax area percentage',
        number: 'Please enter valid number',
        min: 'Please enter minimum 0 value',
      },
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_sale_tax_area').modal('hide');
            $('#loading').css('display', 'none');
            swal('Sales Tax Area', 'Added Successfully ', 'success');
            getSalesTexAreaList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });
  // EDIT SALES TAX AREA FORM
  $("form[name='editsalestexarea']").validate({
    rules: {
      tax_name: 'required',
      tax_value: {
        required: true,
        number: true,
        min: 0,
      },
    },

    messages: {
      tax_name: 'Please enter sales tax area name',
      tax_value: {
        required: 'Please enter sales tax area percentage',
        number: 'Please enter valid number',
        min: 'Please enter minimum 0 value',
      },
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_sales_tax_area').modal('hide');
            $('#loading').css('display', 'none');
            swal('Sales Tax Area', 'Updated Successfully ', 'success');
            getSalesTexAreaList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });
  // EDIT SERVICE AREA FORM
  $("form[name='editservicearea']").validate({
    rules: {
      category_area_name: 'required',
    },
    messages: {
      category_area_name: 'Please enter service area name',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_edit_service_area').modal('hide');
            $('#loading').css('display', 'none');
            // alert(response);
            if (response == 1) {
              swal('Service Area', 'Updated Successfully ', 'success');
            }
            getServiceAreaList();
          },
        });
        return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });
  // ADD CSV FILE FORM
  $("form[name='csvfileimport']").validate({
    rules: {
      csv_file: {
        required: true,
        extension: 'csv',
      },
    },
    messages: {
      csv_file: {
        required: 'Please select file',
        extension: 'Please select only csv files',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // AUTOMATED EMAIL FORM
  $("form[name='automatedemail']").validate({
    rules: {
      job_sheduled: 'required',
      one_day_prior: 'required',
      job_completion: 'required',
    },
    messages: {
      job_sheduled: 'Please enter job Sheduled',
      one_day_prior: 'Please enter 1 day prior to scheduled date',
      job_completion: 'Please enter job completion',
    },
    submitHandler: function (form) {
      form.submit();
    },
  });

  // SMTP FORM
  $("form[name='smtpcredential']").validate({
    rules: {
      smtp_host: 'required',
      smtp_port: {
        required: true,
        number: true,
      },
      smtp_username: 'required',
      smtp_password: 'required',
    },
    messages: {
      smtp_host: 'Please enter smtp host',
      smtp_port: {
        required: 'Please enter smtp port',
        number: 'Please enter valid port number',
      },
      smtp_username: 'Please enter smtp username',
      smtp_password: 'Please enter smtp password',
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // HELP MESSAGE FORM
  $("form[name='HelpMessageForm']").validate({
    rules: {
      message: {
        required: true,
        normalizer: function (value) {
          // Trim the value of the `field` element before
          // validating. this trims only the value passed
          // to the attached validators, not the value of
          // the element itself.
          return $.trim(value);
        },
      },
    },
    messages: {
      message: {
        required: 'Please enter message',
      },
    },
    submitHandler: function (form) {
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission
      $('#loading').css('display', 'block');
      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        dataType: 'JSON',
        success: function (response) {
          $('#loading').css('display', 'none');
          if (response.status == 200) {
            form.reset();
            $('#help_message').modal('hide');

            swal('Help Message', 'sent successfully ', 'success');
          } else if (response.status == 400) {
            swal({
              type: 'error',
              title: 'Oops...',
              text: response.msg,
            });
          } else {
            swal({
              type: 'error',
              title: 'Oops...',
              text: 'Something went wrong!',
            });
          }
        },
      });
      //   return false; // required to block normal submit since you used ajax
    },
  });
  // ASSIGN PROGRAM FORM
  $("form[name='assignProgram']").validate({
    rules: {
      program_id: 'required',
    },
    messages: {
      program_id: 'Please select program',
    },
    submitHandler: function (form) {
      $('#loading').css('display', 'block');
      var property_ids = [];

      $('input:checkbox[name=selectcheckbox]:checked').each(function () {
        property_ids.push($(this).attr('property_id'));
      });

      var program_id = $('#selected_program_id').val();
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method

      $.ajax({
        type: request_method,
        url: post_url,
        data: { property_ids: property_ids, program_id: program_id },
        dataType: 'JSON',
        success: function (response) {
          $('#loading').css('display', 'none');
          form.reset();
          $('#modal_assign_program').modal('hide');
          if (response.status == 200) {
            swal('Program !', response.msg, 'success').then(function () {
              location.reload();
            });
          } else {
            swal({
              type: 'error',
              title: 'Oops...',
              text: 'Something went wrong!',
            });
          }
        },
      });
    },
  });
  // UPDATE PLAN FORM
  $("form[name='updateplan']").validate({
    rules: {
      is_technician_count: {
        integer: true,
        checkedTechnicain: true,
        max: 1000,
        checkboxCheck: '#is_additional',
      },
    },
    messages: {
      is_technician_count: {
        integer: 'Please enter only integer value',
        checkedTechnicain: 'You have already exists more than technician',
        checkboxCheck: 'Please enter number of additional technician',
      },
    },
    submitHandler: function (form) {
      form.submit();
    },
  });
  // REFUND FORM
  $('form#refund_payment_form_full_full').validate({
    // Specify validation rules
    rules: {
      payment_type: 'required',
      partial_payment: 'required',
      payment_method: 'required',
      cc_number_2: 'required',
      check_number_2: 'required',
      other_2: 'required',
    },
    messages: {
      payment_type: 'Please select refund type',
      //partial_payment: {
      //number: 'Please enter valid number',
      //greaterThan: 'Please enter a value greater than 0',
      //lessThanEqualMethod:
      // 'Value should be less than or equal to total refund value',
      //},
      partial_payment: 'Please enter valid number',
      payment_method: 'Please select payment type',
      cc_number_2: 'Please add last 4 digits of card',
      check_number_2: 'Please add check number',
      other_2: 'Please add notes about payment',
    },

    errorPlacement: function (error, element) {
      //  if (element.attr('name') == 'partial_payment') {
      //    error.appendTo('.refund_error');
      //  } else if (element.attr('name') == 'payment_type') {
      //    error.appendTo('.refund_type_error');
      //  } else {
      error.appendTo(element.parent('div'));
      //  }
    },
    submitHandler: function (form) {
      //form.submit();
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission
      // alert(form_data);
      //$('#full_refund_modal').prop('disabled', true);

      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        success: function (data) {
          // console.log(invoice_id);
          $('#loading').css('display', 'none');
          swal('Full Refund', 'Successfully Issued', 'success').then(
              function () {
                console.log(data);
                location.reload();
              }
          );
        },
        error: function (data) {
          console.log(data);
        },
      });
    },
  });
  // FULL REFUND STATUS FORM
  $('form.refund_status_form').validate({
    // Specify validation rules
    rules: {
      payment_method: 'required',
      cc_number_2: 'required',
      check_number_2: 'required',
      other_2: 'required',
    },
    messages: {
      payment_method: 'Please select payment type',
      cc_number_2: 'Please add last 4 digits of card',
      check_number_2: 'Please add check number',
      other_2: 'Please add notes about payment',
    },

    errorPlacement: function (error, element) {
      error.appendTo(element.parent('div'));
    },
    submitHandler: function (form) {
      var post_url = $(form).attr('action');
      var request_method = $(form).attr('method'); //get form GET/POST method
      var form_data = $(form).serialize(); //Encode form elements for submission

      $.ajax({
        type: request_method,
        url: post_url,
        data: form_data,
        success: function (data) {
          // console.log(invoice_id);
          $('#loading').css('display', 'none');
          if (data == 'true') {
            swal('Full Refund', 'Successfully Issued', 'success').then(
                function () {
                  console.log(data);
                  location.reload();
                }
            );
          } else {
            swal({
              type: 'error',
              title: 'Oops...',
              text: 'Something went wrong!',
            });
          }
        },
        error: function (data) {
          console.log(data);
        },
      });
    },
  });

  // ADD SOURCE FORM
  $("form[name='addsource']").validate({
    rules: {
      source_name: 'required',
      source_type: 'required',
    },
    messages: {
      source_name: 'Please enter source name',
      source_type: 'Please enter source type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_source').modal('hide');
            $('#loading').css('display', 'none');
            swal('Source', 'Added Successfully ', 'success');
            getSourceList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // EDIT SOURCE FORM
  $("form[name='editsource']").validate({
    rules: {
      source_name: 'required',
      source_type: 'required',
    },

    messages: {
      source_name: 'Please enter source name',
      source_type: 'Please enter source type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_source').modal('hide');
            $('#loading').css('display', 'none');
            swal('Source', 'Updated Successfully ', 'success');
            getSourceList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // ADD SERVICE TYPE FORM
  $("form[name='addservicetype']").validate({
    rules: {
      service_type_name: 'required',
      service_type: 'required',
    },
    messages: {
      service_type_name: 'Please enter service type name',
      service_type: 'Please enter service type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_service_type').modal('hide');
            $('#loading').css('display', 'none');
            swal('Service Type', 'Added Successfully ', 'success');
            getServiceTypeList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // EDIT SERVICE TYPE FORM
  $("form[name='editservicetype']").validate({
    rules: {
      service_type_name: 'required',
      service_type: 'required',
    },

    messages: {
      service_type_name: 'Please enter service type name',
      service_type: 'Please enter service type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_service_type').modal('hide');
            $('#loading').css('display', 'none');
            swal('Service Type', 'Updated Successfully ', 'success');
            getServiceTypeList();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // ADD COMMISSION FORM
  $("form[name='addcommission']").validate({
    rules: {
      commission_name: 'required',
      commission_value: {
        required: true,
        number: true,
        min: 0,
      },
      commission_type: 'required',
    },
    messages: {
      commission_name: 'Please enter commission name',
      commission_value: {
        required: 'Please enter commission value',
        number: 'Please enter valid number',
        min: 'Please enter minimum 0 value',
      },
      commission_type: 'Please select commission type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_commission').modal('hide');
            $('#loading').css('display', 'none');
            swal('Commission', 'Added Successfully ', 'success');
            getCommissionList();
            location.reload();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // EDIT COMMISSION FORM
  $("form[name='editcommission']").validate({
    rules: {
      commission_name: 'required',
      commission_value: {
        required: true,
        number: true,
        min: 0,
      },
      commission_type: 'required',
    },

    messages: {
      commission_name: 'Please enter commission name',
      commission_value: {
        required: 'Please enter commission value',
        number: 'Please enter valid number',
        min: 'Please enter minimum 0 value',
      },
      commission_type: 'Please select commission type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_commission').modal('hide');
            $('#loading').css('display', 'none');
            swal('Commission', 'Updated Successfully ', 'success');
            getCommissionList();
            location.reload();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // ADD BONUS FORM
  $("form[name='addbonus']").validate({
    rules: {
      bonus_name: 'required',
      bonus_value: {
        required: true,
        number: true,
        min: 0,
      },
      bonus_type: 'required',
    },
    messages: {
      bonus_name: 'Please enter bonus name',
      bonus_value: {
        required: 'Please enter bonus value',
        number: 'Please enter valid number',
        min: 'Please enter minimum 0 value',
      },
      bonus_type: 'Please select bonus type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_add_bonus').modal('hide');
            $('#loading').css('display', 'none');
            swal('Bonus', 'Added Successfully ', 'success');
            getBonusList();
            location.reload();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });

  // EDIT BONUS FORM
  $("form[name='editbonus']").validate({
    rules: {
      bonus_name: 'required',
      bonus_value: {
        required: true,
        number: true,
        min: 0,
      },
      bonus_type: 'required',
    },

    messages: {
      bonus_name: 'Please enter bonus name',
      bonus_value: {
        required: 'Please enter bonus value',
        number: 'Please enter valid number',
        min: 'Please enter minimum 0 value',
      },
      bonus_type: 'Please select bonus type',
    },
    submitHandler: function (form) {
      type = $(form).attr('form_ajax');
      if (type == 'ajax') {
        var post_url = $(form).attr('action');
        var request_method = $(form).attr('method'); //get form GET/POST method
        var form_data = $(form).serialize(); //Encode form elements for submission
        $('#loading').css('display', 'block');
        $.ajax({
          type: request_method,
          url: post_url,
          data: form_data,
          success: function (response) {
            form.reset();
            $('#modal_bonus').modal('hide');
            $('#loading').css('display', 'none');
            swal('Bonus', 'Updated Successfully ', 'success');
            getBonusList();
            location.reload();
          },
        });
        //   return false; // required to block normal submit since you used ajax
      } else {
        form.submit();
      }
    },
  });
});

// Inventory module
// ADD NEW ITEM TYPE
$("form[name='additemtype']").validate({
  // Specify validation rules
  rules: {
    item_type_name: "required",
    item_type_description: "required",
  },
  messages: {
    item_type_name: "Please enter item type name",
    item_type_description: "Please add description",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'Item Type',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});
// ADD NEW ITEM
$("form[name='additem']").validate({
  // Specify validation rules
  rules: {
    item_name: "required",
    // code_type: "required",
    item_number: "required",
    brand_id: "required",
    item_type_id: "required",
    sale_price: "required",
    sale_tax: "required",
    item_description: "required",
    // weight: "required",
    // width: "required",
    // height: "required",
    // depth: "required",
    // min_alert: "required",
    // max_alert: "required",
    notes: "required",
  },
  messages: {
    item_name: "Please enter item type name",
    // code_type: "Please select barcode type",
    item_number: "Please add item #",
    brand_id: "Please select brand",
    item_type_id: "Please select item types",
    sale_price: "Please add sale price",
    sale_tax: "Please add sale tax",
    item_description: "Please add description",
    // weight: "Please add weight",
    // width: "Please add width",
    // height: "Please add height",
    // depth: "Please add dept",
    // min_alert: "Please add minimum qty alert",
    // max_alert: "Please add maximum qty alert",
    notes: "Please add notes",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Item',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW PURCHASE
$("form[name='addnewpurchase']").validate({
  // Specify validation rules
  rules: {
    purchase_order_number: "required",
    location: "required",
    vendor: "required",
    shipping_cost: "required",
    discount: "required",
    tax: "required",
    // new_purchase_order_notes: "required",
  },
  messages: {
    purchase_order_number: "Please enter a purchase order number",
    location: "Please select a location",
    vendor: "Please select vendor",
    shipping_cost: "Please add shipping cost",
    discount: "Please add discount",
    tax: "Please add tax",
    // new_purchase_order_notes: "Please add purchase order notes",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Purchase',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW ADJUSTMENT
$("form[name='addnewadjustment']").validate({
  // Specify validation rules
  rules: {
    location_id: "required",
  },
  messages: {
    location_id: "Please select a location",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Adjustment',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW TRANSFER
$("form[name='addnewtransfer']").validate({
  // Specify validation rules
  rules: {
    from_sub_location_id: "required",
    to_sub_location_id: "required",
  },
  messages: {
    from_sub_location_id: "Please select a source location",
    to_sub_location_id: "Please select a target location",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Transfer',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW VENDOR
$("form[name='addnewvendor']").validate({
  // Specify validation rules
  rules: {
    vendor_name: "required",
    internal_name: "required",
    company_name: "required",
    vat_number: "required",
    vendor_email_address: "required",
    vendor_phone_number: "required",
    vendor_street_address: "required",
    vendor_city: "required",
    // vendor_country: "required",
    vendor_state: "required",
    vendor_zip_code: "required",
    // custom_field1: "required",
    // custom_field2: "required",
    // custom_field3: "required",
    // notes: "required",
  },
  messages: {
    vendor_name: "Please enter vendor name",
    internal_name: "Please enter internal name",
    company_name: "Please enter company name",
    vat_number: "Please add vat number",
    vendor_email_address: "Please add email address",
    vendor_phone_number: "Please add phone number",
    vendor_street_address: "Please add street address",
    vendor_city: "Please add city",
    // vendor_country: "Please add country",
    vendor_state: "Please add state",
    vendor_zip_code: "Please add zip code",
    // custom_field1: "Please add custom field1",
    // custom_field2: "Please add custom field2",
    // custom_field3: "Please add custom field3",
    // notes: "Please add purchase order notes",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Vendor',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW BRAND
$("form[name='addnewbrand']").validate({
  // Specify validation rules
  rules: {
    brand_name: "required",
    brand_description: "required",
  },
  messages: {
    brand_name: "Please enter a brand name",
    brand_description: "Please add brand description",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Brand',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW LOCATION
$("form[name='addnewlocation']").validate({
  // Specify validation rules
  rules: {
    location_name: "required",
    location_street: "required",
    location_city: "required",
    location_state: "required",
    location_zip: "required",
    location_country: "required",
    location_phone: "required",
  },
  messages: {
    location_name: "Please enter a location name",
    location_street: "Please enter a location address",
    location_city: "Please enter a location city",
    location_state: "Please enter a location state",
    location_zip: "Please enter a zip code",
    location_country: "Please enter a location country",
    location_phone: "Please enter a location phone number",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Location',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload('inventory/Frontend/Locations');
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD UPDATE LOCATION
$("form[name='editlocation']").validate({
  // Specify validation rules
  rules: {
    location_name: "required",
    location_street: "required",
    location_city: "required",
    location_state: "required",
    location_zip: "required",
    location_country: "required",
    location_phone: "required",
  },
  messages: {
    location_name: "Please enter a location name",
    location_street: "Please enter a location address",
    location_city: "Please enter a location city",
    location_state: "Please enter a location state",
    location_zip: "Please enter a zip code",
    location_country: "Please enter a location country",
    location_phone: "Please enter a location phone number",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'Location Updated',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW SUB LOCATION
$("form[name='addsublocation']").validate({
  // Specify validation rules
  rules: {
    location_id: "required",
    sub_location_name: "required",
  },
  messages: {
    location_id: "Please select a location name",
    sub_location_name: "Please enter a sub location",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Sub Location',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload('inventory/Frontend/Locations');
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD UPDATE SUB LOCATION
$("form[name='editsublocation']").validate({
  // Specify validation rules
  rules: {
    location_id: "required",
    sub_location_name: "required",
  },
  messages: {
    location_id: "Please select a location name",
    sub_location_name: "Please enter a sub location",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'Sub Location ',
              'Successfully Updated',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD NEW VENDOR
$("form[name='addnewvendor1']").validate({
  // Specify validation rules
  rules: {
    vendor_number: "required",
    vendor_name: "required",
    internal_name: "required",
    company_name: "required",
    vendor_email_address: "required",
    vendor_phone_number: "required",
    vendor_street_address: "required",
    vendor_city: "required",
    vendor_state: "required",
    vendor_zip_code: "required",
    // vendor_country: "required",
    // custom_field1: "required",
    // custom_field2: "required",
    // custom_field3: "required",
    // notes: "required",
  },
  messages: {
    vendor_number: "Please enter a vendor #",
    vendor_name: "Please enter a vendor name",
    internal_name: "Please enter a internal name",
    company_name: "Please enter a company name",
    vendor_email_address: "Please enter a email address",
    vendor_phone_number: "Please enter a phone number",
    vendor_street_address: "Please enter a vendor address",
    vendor_city: "Please enter a vendor city",
    vendor_state: "Please enter a vendor state",
    vendor_zip_code: "Please enter a zip code",
    // vendor_country: "Please enter a location country",
    // custom_field1: "Please enter a custom field 1",
    // custom_field1: "Please enter a custom field 2",
    // custom_field1: "Please enter a custom field 3",
    // notes: "Please enter notes",
  },

  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'New Vendor',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload('inventory/Frontend/Vendors');
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
});

// ADD UPDATE VENDOR
$("form[name='editvendor']").validate({
  // Specify validation rules
  rules: {
    vendor_name: "required",
    vendor_number: "required",
    internal_name: "required",
    company_name: "required",
    vendor_email_address: "required",
    vendor_phone_number: "required",
    vendor_street_address: "required",
    vendor_city: "required",
    vendor_state: "required",
    vendor_zip_code: "required",
    // vendor_country: "required",
    // custom_field1: "required",
    // custom_field2: "required",
    // custom_field3: "required",
    // notes: "required",
  },
  messages: {
    vendor_name: "Please enter a vendor name",
    vendor_number: "Please enter a vendor #",
    internal_name: "Please enter a internal name",
    company_name: "Please enter a company name",
    vendor_email_address: "Please enter a email address",
    vendor_phone_number: "Please enter a phone number",
    vendor_street_address: "Please enter a vendor address",
    vendor_city: "Please enter a vendor city",
    vendor_state: "Please enter a vendor state",
    vendor_zip_code: "Please enter a zip code",
    // vendor_country: "Please enter a location country",
    // custom_field1: "Please enter a custom field 1",
    // custom_field1: "Please enter a custom field 2",
    // custom_field1: "Please enter a custom field 3",
    // notes: "Please enter notes",
  },


  errorPlacement: function (error, element) {
    error.appendTo(element.parent('div'));
  },
  submitHandler: function (form) {
    var post_url = $(form).attr('action');
    var request_method = $(form).attr('method'); //get form GET/POST method
    var form_data = $(form).serialize(); //Encode form elements for submission

    $.ajax({
      type: request_method,
      url: post_url,
      data: form_data,
      dataType: "json",
      success: function (response) {
        // if (response.status==200) {
        console.log(response.status);
        $("#loading").css("display","none");
        if (response.status==200) {
          swal(
              'Location Updated',
              'Successfully Added',
              'success'
          ).then(function() {
            // console.log(data);
            location.reload();
          });
        } else {
          swal({
            type: 'error',
            title: 'Oops...',
            text: 'Something went wrong!'
          })
        }

      },
      error: function (data) {
        console.log(data);
      },

    });
  },
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


