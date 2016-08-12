(function($){
	$(document).ready(function(){
		$('#general_checkbox').on('change', function(){
			$('.unapprove_checkbox').each(function(index){
				$(this).prop('checked', !$(this).prop('checked'));
			});
		});

		$('.search_employee').select2();

		$('#search_employee').on('click', function(){
			$.post($(this).data('url'), {employee_id: $('.search_employee').val(), batch_id: $(this).data('batch')})
			.done(function(response){
				$('#table_body').html(response);
			})
			.fail(function(){
				alert('An internal error has occured. Please try again.');
			});
		});

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

			$.post($(this).data('url'))
			.done(function(response){
				wndw.document.write(response);
				wndw.document.close();
				wndw.focus();
				wndw.print();
				wndw.close();
			});
		});
	});
})(jQuery);