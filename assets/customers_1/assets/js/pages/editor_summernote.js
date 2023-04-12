/* ------------------------------------------------------------------------------
*
*  # Summernote editor
*
*  Specific JS code additions for editor_summernote.html page
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */


$(function() {


    // Basic editors
    // ------------------------------

    // Default initialization
    $('.summernote').summernote({
         toolbar: false,
           placeholder: 'Job Sheduled'
    });

    $('.summernote2').summernote({
         toolbar: false,
         placeholder : '1 Day Prior To Scheduled Date',
    });
  

 $("#one_day_prior").click(function(){ 
    $( ".demo_btn" ).trigger( "click" );
 })


    $('.summernote3').summernote({
         toolbar: false,         
         placeholder : '1 Hour Prior To Scheduled Date',
    });
	$('.summernote4').summernote({
         toolbar: false,
         placeholder : 'Program Assigned'
    });
	
	$('.summernote5').summernote({
         toolbar: false,
         placeholder : 'Estimate Accepted'
    });

     $('.summernote_property').summernote({
         toolbar: false
    });





    // Control editor height
    $('.summernote-height').summernote({
        height: 400
    });


    // Air mode
    $('.summernote-airmode').summernote({
        airMode: true
    });



    // Click to edit
    // ------------------------------

    // Edit
    $('#edit').on('click', function() {
        $('.click2edit').summernote({focus: true});
    })

    // Save
    $('#save').on('click', function() {
        var aHTML = $('.click2edit').code(); //save HTML If you need(aHTML: array).
        $('.click2edit').destroy();
    })



    // Related form components
    // ------------------------------

    // Styled checkboxes/radios
    $(".link-dialog input[type=checkbox], .note-modal-form input[type=radio]").uniform({
        radioClass: 'choice'
    });


    // Styled file input
    $(".note-image-input").uniform({
        fileButtonClass: 'action btn bg-warning-400'
    });

});
