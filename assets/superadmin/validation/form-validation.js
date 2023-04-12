
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


  $("form[name='addcompany']").validate({
    // Specify validation rules
    rules: {
      
      company_name: "required",
      company_address: "required",
      company_email: "required",
      web_address: {
       // required : true,
       url : true
      },

      start_location : "required",
      end_location : "required",
      user_first_name :"required",
      user_last_name : "required",
      email : {
        required : true,
        email : true
      },
      phone : {
        required : true,
        number : true,
      },
      password: {
            required :true,
            minlength : 5
          },
      confirm_password: {
            required : true,
            equalTo : "#password"
          },

    },
    messages: {
      company_name: "Please enter company name",
      company_address: "Please enter company address",
      company_email: "Please enter company email",
      web_address: {
       // required : "Please enter web address",
       url : "Please enter valid url"
      }, 
      start_location : "Please enter start location",
      end_location : "Please enter end location",
      user_first_name :"Please enter user first name",
      user_last_name : "Please enter user last name",
      email : {
        required : "Please enter user email address",
        email : "Please enter valid email  address"
      },
      phone : {
        required : "Please enter user phone number",
        number : "Please enter valid number",
      },
      password: {
             required: "Please provide a password",
             minlength: "Your password must be at least 5 characters long",
          },
      confirm_password: {
            required :   "Please provide confirm password",
            equalTo :   "Password did not match",
          },
    },
    submitHandler: function(form) {
      form.submit();
    }
  });



  ///////////////////////////// company details  //////////////////

  $("form[name='editcompany']").validate({
      // Specify validation rules
      rules: {         
          company_name: "required",
          company_address: "required",
          company_email: {
            required : true,
            email : true,
          },
         
          web_address : {
            // required  : true,
            url : true,
          },
          start_location : "required",
          end_location : "required",
      },
      messages: {

          company_name: "Please enter company name",
          company_address: "Please enter company address",
          company_email: {
             required : "Please provide email address",
             email :   "Please enter a valid email address",
          },

           web_address : {
            // required  : "Please enter web address",
            url : "Please enter a valid url",
          },

           start_location : "Please enter start location",
           end_location : "Please enter end location",
      },
      submitHandler: function(form) {
         form.submit();

      }
    });



///////////////////////////// end company details ///////////


///// add user //////////////////

  $("form[name='edituser']").validate({
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
         
         
      },
      submitHandler: function(form) {
         form.submit();

      }
    });



///////////////////////////////////Add Customer///////////////////


//////////////////////////////smtp automated //////////////////////////

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

///////////////////////////////smtp file //////////////////////////
  jQuery.validator.addMethod("checkedTechnicain", function(value, element) {
        var isSuccess = false;           
        var company_id = $('#company_id').val();           
        $.ajax({
            type: 'POST',
            url:  location.origin+'/superadmin/Managesubscription/getTechCount/'+company_id,
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

        },

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




///////////////////////////////add csv file //////////////////////////

$("form[name='addnote']").validate({

      rules: {
      
       note_description : {
         required :   true,
          normalizer: function( value ) {
            // Trim the value of the `field` element before
            // validating. this trims only the value passed
            // to the attached validators, not the value of
            // the element itself.
            return $.trim( value );
          }

        }
       },

       messages: {
        note_description : {
         required : "Please enter note here"
        }
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
 
