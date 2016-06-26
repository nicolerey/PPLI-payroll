(function($){
	$(document).ready(function(){
		$('#approve_button').on('click', function(){
			var msgBox = $('.alert-danger').addClass('hidden');

			$.post($('form').attr('action'), $('form').serialize())
			.done(function(response){
				if(response.result)
					location.reload();

				msgBox.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
				$('html, body').animate({scrollTop: 0}, 'slow');
			})
			.fail(function(){
				alert('An internal server error has occured');
			});
		});

		$('#print_button').on('click', function(){
			var wndw = window.open();
			$(this).data('url')+"/"+$('#batch_id').val();

			$.post($(this).data('url')+"/"+$('#batch_id').val())
			.done(function(response){
				wndw.document.write(response);
				/*wndw.document.close();
				wndw.focus();
				wndw.print();
				wndw.close();*/
			});
		})
	})
})(jQuery)