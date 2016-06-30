function delete_report(element){
	if(confirm('Are you sure?')){
		$.post($(element).data('url'), {id: $(element).attr('pk')})
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