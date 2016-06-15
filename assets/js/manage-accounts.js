(function($){
	$(document).ready(function(){	

		$('form#account').submit(function(e){

			e.preventDefault();

			var that = $(this),
				submitBtn = that.find('[type=submit]'),
				msgBox = $('.alert');

			submitBtn.attr('disabled', 'disabled');
			msgBox.addClass('hidden');

			$.post(that.data('action'), that.serialize())
			.done(function(response){
				msgBox.removeClass('hidden');
				if(response.result){
					window.location.reload();
					return;
				}
				msgBox.removeClass('alert-success').addClass('alert-danger').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
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