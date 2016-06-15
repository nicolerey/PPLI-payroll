function delete_employee(element){
	if(confirm('Are you sure?')){
		var delete_url = $(element).attr('delete_url');
		$.post(delete_url)
		.done(function(response){
			if(response.result)
				$(element).closest('.employee_row_fields').remove();
			else{
				$('.alert-danger').removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
				$('html, body').animate({scrollTop: 0}, 'slow');
			}
		})
		.fail(function(){
			alert('An internal error has occured. Please try again.');
		});
	}
}

$(document).ready(function(){
	var lockUrl = $('#lockUrl').data('value');

	$('.lock').change(function(){
		var id = $(this).closest('tr').data('pk');
		$.post(lockUrl, {id:id})
		.done(function(response){
			
		})
		.fail(function(){
			alert('An internal server error has occured. Please try again');
		});
	});
})