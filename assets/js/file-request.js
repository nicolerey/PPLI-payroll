
$(document).ready(function(){	

	var dateFormat = 'MM/DD/YYYY';

	function customTitle(){
		if($('select[name=type]').val()==='o'){
			$('input[name=custom_type_name]').removeAttr('disabled');
			return;
		}
		$('input[name=custom_type_name]').attr('disabled', 'disabled');
	}

	function halfday(){
		var start = moment($('[name=date_start]').val(), dateFormat),	
			end = moment($('[name=date_end]').val(), dateFormat),
			diff = start.diff(end, 'days');
		if(diff === 0){
			$('.halfday').removeAttr('disabled', 'disabled').closest('.form-group').slideDown();
		}else{
			$('.halfday').attr('disabled', 'disabled').closest('.form-group').slideUp().end().prop('checked', false)
		}
	}

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

	$('select[name=type]').change(customTitle);
	$('.datepicker').datepicker().on('changeDate', halfday);

	customTitle();
	halfday();
	
});