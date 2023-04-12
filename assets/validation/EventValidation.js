

$(document).ready(function(){

$('#submit').click(function(){

var hotspot_id =  $('#hotspot_id').val();
var title =  $('#titleid').val();
var description =  $('#descriptionid').val();
var starttime =  $('#starttimeid').val();
var endtime =  $('#endtimeid').val();
var datevar =  $('#datetimepicker1').val();


    if(hotspot_id != 0){
        $('.errorHotspot').html('');
       
    }else{
       $('.errorHotspot').html('Hotspot is required');
        return false;
    }

    if(title == ""){
        $('.errorTitle').html('Title is required');
        return false;
    }else{
        $('.errorTitle').html('');
    }

    if(description == ""){
        $('.errorDescription').html('Description is required');
        return false;

    }else{
        $('.errorDescription').html('');
    }

    if(starttime == ""){
        $('.errorStartTime').html('Start time is required');
        return false;

    }else{
        $('.errorStartTime').html('');
    }

    if(endtime == ""){
        $('.errorEndTime').html('End time is required');
        return false;

    }else{
        $('.errorEndTime').html('');
    }

    if(datevar == ""){
        $('.errorDate').html('Date is required');
        return false;

    }else{
        $('.errorDate').html('');
    }
});







});

