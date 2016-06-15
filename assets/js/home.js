(function($){
	$(document).ready(function(){	

		$('form#change-pw').submit(function(e){
			e.preventDefault();

			var that = $(this),
				submitBtn = that.find('[type=submit]'),
				msgBox = $('.alert');

			submitBtn.attr('disabled', 'disabled');
			msgBox.addClass('hidden');

			$.post(that.data('action'), that.serialize())
			.done(function(response){
				if(response.result){
					msgBox.removeClass('hidden alert-danger').addClass('alert-success').find('ul').html('<li>Password changed successfully!</li>');
					that.find('[type=password]').val('');
					return;
				}
				msgBox.removeClass('hidden alert-success').addClass('alert-danger').find('ul').html('<li>'+response.messages.join('</li><li>')+'</li>');
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