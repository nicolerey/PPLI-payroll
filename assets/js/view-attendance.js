var attendance_row_index = [];

$(document).ready(function(){    
    $('.search_employee').select2();

    $('.authorization_fail').hide();

	$.fn.editable.defaults.mode = 'popup';
	
	$('.editable_time').editable({
		format: 'YYYY-MM-DD hh:mm A',   
        template: 'YYYY - MM - DD hh : mm  A',
        success: function(){
            var row_id = $(this).attr('table-row');

            if($(this).hasClass('time_in'))
                checkAndAdd(row_id, 1, false);
            else
                checkAndAdd(row_id, false, 1);

            $('.save_attendance').prop('disabled', false);
            return true;
        },
        combodate: {
                minuteStep: 1,
				maxYear: '2030',
				weekStart: 0
           }
    });
	
	$('.datepicker').datepicker();

    $('#save').on('click', function(){
        var authorize_url = $(this).attr('authorize-url');
        var save_url = $(this).attr('save-url');

        $.post(authorize_url, {password: $('#password').val()}, function(response){
            if(response.status){
                attendance_row_index.forEach(function(el, index){
                    attendance_row_index[index].last_approved_by = response.approved_by;

                    if(typeof el.datetime_in !== 'undefined'){
                        attendance_row_index[index].id = $(".time_in").eq(el.row_id).attr('attendance-id');
                        attendance_row_index[index].datetime_in = $(".time_in").eq(el.row_id).text();
                    }

                    if(typeof el.datetime_out !== 'undefined'){
                        attendance_row_index[index].id = $(".time_out").eq(el.row_id).attr('attendance-id');
                        attendance_row_index[index].datetime_out = $(".time_out").eq(el.row_id).text();
                    }

                    delete attendance_row_index[index].row_id;
                });

                $.post(save_url, {data:attendance_row_index}, function(){
                    location.reload();
                });
            }
            else
                $('.authorization_fail').show();
        });
    });
});

function checkAndAdd(row_id, time_in, time_out) {
    var rtrn = false;
    attendance_row_index.forEach(function(el, index){
        if(el.row_id===row_id)
            rtrn = index;
    });

    if(rtrn===false){
        var changes = {};
        changes.row_id = row_id;
        if(time_in)
            changes.datetime_in = "";
        else if(time_out)
            changes.datetime_out = "";
        attendance_row_index.push(changes);
    }
    else{
        if(time_in)
            attendance_row_index[rtrn].datetime_in = "";
        else if(time_out)
            attendance_row_index[rtrn].datetime_out = "";
    }
}