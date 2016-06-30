function show_field(element)
{
	if($(element).val()=='dep'){
		$('.dep_field').show();
		$('.emp_field').hide();
	}
	else{
		$('.dep_field').hide();
		$('.emp_field').show();
	}
}

function detect_change(){
	$('.change').val(1);
}

$(document).ready(function(){
	$('.type_fields').hide();

	$('.datepicker').datepicker();

	$('form').submit(function(e){
		e.preventDefault();

		var that = $(this),
			submitBtn = that.find('[type=submit]'),
			msgBox = $('.alert-danger');

		submitBtn.attr('disabled', 'disabled');
		msgBox.addClass('hidden');

		$.post(that.data('action'), that.serialize())
		.done(function(response){
			console.log(response);
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

	$('.approve').on('click', function(){
		var that = $('form'),
			submitBtn = that.find('[type=submit]'),
			msgBox = $('.alert-danger');

		submitBtn.attr('disabled', 'disabled');
		msgBox.addClass('hidden');

		$.post($(this).data('url'), {id: $('.id').val(), approve: true})
		.done(function(response){
			console.log(response);
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
});
