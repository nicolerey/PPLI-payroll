(function($){
	$(document).ready(function(){	

		$('form').submit(function(e){

			e.preventDefault();

			var that = $(this),
				submitBtn = that.find('[type=submit]'),
				msgBox = $('.alert-danger');

			submitBtn.attr('disabled', 'disabled');
			msgBox.addClass('hidden');

			$.post(that.data('action'), that.serialize())
			.done(function(response){
				if(response.result){
					window.location.href = $('.cancel').attr('href');
					return;
				}
				msgBox.removeClass('hidden').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
				$('html, body').animate({scrollTop: 0}, 'slow');
			})
			.fail(function(){
				alert('An internal server error has occured');
			}).always(function(){
				submitBtn.removeAttr('disabled');
			});

		});

	})
})(jQuery)