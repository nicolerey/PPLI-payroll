(function($){
	$(document).ready(function(){	

		var index = $('[data-name=index]').data('value');

		$('.datepicker').datepicker();
		$('.pformat').priceFormat({prefix:''});

		$('#add-particulars').click(function(){
			var tr = $('table#particulars > tbody > tr');
			if(tr.length === 1 && tr.hasClass('hidden')){
				tr.removeClass('hidden').find('select,input').removeAttr('disabled');
			}else{
				var clone = $(tr[0]).clone();
				clone.find('input[type=hidden]').remove();
				clone.find('input,select').val('').attr('name', function(){
					return $(this).data('name').replace('idx', index);
				});
				clone.find('.pformat').priceFormat({prefix:''});
				clone.appendTo('table#particulars > tbody');
				index++;
			}
		});

		$('table#particulars').on('click', '.remove', function(){
			var tr = $('table#particulars > tbody > tr');
			if(tr.length === 1){
				tr.addClass('hidden').find('input,select').val('').attr('disabled', 'disabled');
				tr.find('[type=hidden]').remove();
			}else{
				$(this).closest('tr').remove();
			}
		});

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