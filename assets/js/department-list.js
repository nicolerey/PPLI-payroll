function delete_department(element){
	if(confirm('Are you sure?')){
		var delete_url = $(element).attr('delete_url');
		$.post(delete_url)
		.done(function(response){
			if(response.result)
				$(element).closest('tr').remove();
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