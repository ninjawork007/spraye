

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


     var startTime = new Date().setHours(GetHours(starttime), GetMinutes(starttime), 0);
        var endTime = new Date(startTime)
        endTime = endTime.setHours(GetHours(endtime), GetMinutes(endtime), 0);
        // if (startTime > endTime) {
        //     alert("Start Time is greater than end time");
        // }
        if (startTime == endTime) {
            $('.errorEndTime').html('End time and Start time should not be similar.');
            return false;
        }
        if (startTime > endTime) {
             $('.errorEndTime').html('End time should be greater than Start time');
        return false;
        }



});




 function GetHours(d) {
        var h = parseInt(d.split(':')[0]);
        if (d.split(':')[1].split(' ')[1] == "PM") {
            h = h + 12;
        }
        return h;
    }
    function GetMinutes(d) {
        return parseInt(d.split(':')[1].split(' ')[0]);
    }




});

