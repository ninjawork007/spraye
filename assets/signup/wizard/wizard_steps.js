/* ------------------------------------------------------------------------------
*
*  # Steps wizard
*
*  Specific JS code additions for wizard_steps.html page
*
*  Version: 1.1
*  Latest update: Dec 25, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {


 
$(document).ready(function () {

    $("input").on("keypress", function(e) {
      
         if (e.which === 32 && !this.value.length)
            e.preventDefault();
    });
  });



 jQuery.validator.addMethod("email", function(value, element) {
  // allow any non-whitespace characters as the host part
  return this.optional( element ) || /^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/.test( value );
});


      jQuery.validator.addMethod("companyEmailCheck", function(value, element) {
         var isSuccess = false;           
        $.ajax({
            type: 'POST',
            url: location.origin+'/superadmin/auth/CheckComapnyEmail',
            data : {company_email : value  },
            async: false, 
            dataType : 'JSON',            
            success: function (response) {
                if (response.status==200) {
                   
                    isSuccess  = true;
                } else {

                    isSuccess  = false;
                }
            }
          });
         return isSuccess;        
    });


      jQuery.validator.addMethod("loginUserEmail", function(value, element) {
         var isSuccess = false;           
        $.ajax({
            type: 'POST',
            url:  location.origin+'/superadmin/auth/CheckUserEmail',
            data : {email : value  },
            async: false, 
            dataType : 'JSON',            
            success: function (response) {
                if (response.status==200) {
                   
                    isSuccess  = true;
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



    // Show form
    var form = $(".steps-validation").show();


    // Initialize wizard
    $(".steps-validation").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="number">#index#</span> #title#',
        autoFocus: true,

         labels: {
               pagination: "Pagination",
                finish: "SIGN UP NOW!",
                next: "CONTINUE",
                previous: "Previous",
              },

        onStepChanging: function (event, currentIndex, newIndex) {



            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }

            // Forbid next action on "Warning" step if the user is to young
            if (currentIndex === 0 && $("#new_password").val() != $("#confirm_password").val()) {

                return false;
            }

            
            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {

                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }

            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },

        onStepChanged: function (event, currentIndex, priorIndex) {

            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18 && $("#new_password").val() == $("#confirm_password").val()) {
                form.steps("next");
            }


            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                form.steps("previous");
            }
        },

        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },

        onFinished: function (event, currentIndex) {
            counting();  
          $('.steps-validation').find("#payButton").trigger('click');            
        }
    });


    // Initialize validation
    $(".steps-validation").validate({
        ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
        errorClass: 'validation-error-label',
        successClass: 'validation-valid-label',
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass);
        },

        // Different components require proper error label placement
        errorPlacement: function(error, element) {

            // Styled checkboxes, radios, bootstrap switch
            if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
                if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo( element.parent().parent().parent().parent() );
                }
                 else {
                    error.appendTo( element.parent().parent().parent().parent().parent() );
                }
            }

            // Unstyled checkboxes, radios
            else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                error.appendTo( element.parent().parent().parent() );
            }

            // Input with icons and Select2
            else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                error.appendTo( element.parent() );
            }

            // Inline checkboxes, radios
            else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                error.appendTo( element.parent().parent() );
            }

            // Input group, styled file input
            else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                error.appendTo( element.parent().parent() );
            }

            else {
                error.insertAfter(element);
            }
        },
        rules: {
            
          
            password : {
              minlength: 5,
            },
            confirm_password : {
              equalTo: "#new_password"
            },
            company_email : {
                companyEmailCheck: true,
                loginUserEmail : true,
            },
            phone : {
              required :true,
              number : true
            },
            is_technician_count : {
                integer : true,                
                max : 10000,
                checkboxCheck : "#is_additional"
            },
        },

        messages: {
             
              company_email: {
                companyEmailCheck: "Company email already exits",
                loginUserEmail : "Email already exits",
              },

              is_technician_count : {
                integer : "Please enter only integer value",                
                checkboxCheck : "Please enter number of additional technician",
              },

              
        },

    });




  


    // Wizard examples
    // ------------------------------

    // Basic wizard setup
    $(".steps-basic").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="number">#index#</span> #title#',
        labels: {
            finish: 'Submit'
        },
        onFinished: function (event, currentIndex) {
            alert("Form submitted.");
        }
    });


    // Async content loading
    $(".steps-async").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        transitionEffect: "fade",
        titleTemplate: '<span class="number">#index#</span> #title#',
        labels: {
            finish: 'Submit'
        },
        onContentLoaded: function (event, currentIndex) {
            $(this).find('select.select').select2();

            $(this).find('select.select-simple').select2({
                minimumResultsForSearch: Infinity
            });

            $(this).find('.styled').uniform({
                radioClass: 'choice'
            });

            $(this).find('.file-styled').uniform({
                fileButtonClass: 'action btn bg-warning'
            });
        },
        onFinished: function (event, currentIndex) {
            alert("Form submitted.");
        }
    });


    // Saving wizard state
    $(".steps-state-saving").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        saveState: true,
        titleTemplate: '<span class="number">#index#</span> #title#',
        autoFocus: true,
        onFinished: function (event, currentIndex) {
            alert("Form submitted.");
        }
    });


    // Specify custom starting step
    $(".steps-starting-step").steps({
        headerTag: "h6",
        bodyTag: "fieldset",
        startIndex: 2,
        titleTemplate: '<span class="number">#index#</span> #title#',
        autoFocus: true,
        onFinished: function (event, currentIndex) {
            alert("Form submitted.");
        }
    });


    //
    // Wizard with validation
    //


    // Initialize plugins
    // ------------------------------

    // Select2 selects
    $('.select').select2();


    // Simple select without search
    $('.select-simple').select2({
        minimumResultsForSearch: Infinity
    });


    // Styled checkboxes and radios
    $('.styled').uniform({
        radioClass: 'choice'
    });


    // Styled file input
    $('.file-styled').uniform({
        fileButtonClass: 'action btn bg-blue'
    });
    
});
