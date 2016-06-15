function calculate_particular_amount(element, type){
	var particular_rate = 0;
	var particular_days_rendered = 0;
	var particular_unit = 0;

	particular_rate = $(element).parent().parent().find('.particular_rate').val().replace(",", "");
	if(type){
		particular_days_rendered = $(element).parent().parent().find('.particular_days_rendered').val();
		particular_unit = $(element).parent().parent().find('.particular_unit').val();
	}
	else{
		particular_days_rendered = $(element).parent().parent().find('.particular_days_rendered').html();
		particular_unit = $(element).parent().parent().find('.particular_unit').val();
	}

	var particular_amount = particular_rate * particular_days_rendered * particular_unit;
	$(element).parent().parent().find('.particular_amount').html(commaSeparateNumber(particular_amount.toFixed(2)));

	calculate_total_amount();
}

function calculate_total_amount(){
	var total_additional_amount = 0;
	$('.particular_amount').each(function(){
		total_additional_amount += Number(($(this).html()).replace(",", ""));
	});

	var total_deduction_amount = 0;
	$('.deduction_particular_amount').each(function(){
		total_deduction_amount += Number(($(this).val()).replace(",", ""));
	});
	$('.loan_payment_amount').each(function(){
		total_deduction_amount += Number(($(this).html()).replace(",", ""));
	});

	var net_pay = total_additional_amount - total_deduction_amount;

	$('.total_additional').html(commaSeparateNumber(total_additional_amount.toFixed(2)));
	$('.net_pay').html(commaSeparateNumber(net_pay.toFixed(2)));
}

function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
}

function add_particular_group(){
	var dynamic_add_particulars = $('.dynamic_add_particulars').first().clone().removeClass('hidden');

	dynamic_add_particulars.find('.additional_name').attr('name', 'additional_name[]');
	dynamic_add_particulars.find('.particular_type').attr('name', 'particular_type[]');
	dynamic_add_particulars.find('.particular_unit').attr('name', 'particular_units[]');
	dynamic_add_particulars.find('.particular_rate').attr('name', 'additional_particular_rate[]');
	dynamic_add_particulars.find('.particular_days_rendered').attr('name', 'particular_days_rendered[]');
	dynamic_add_particulars.find('.pformat').priceFormat({prefix:''});

	$('.additional_particulars_container').append(dynamic_add_particulars);
}

function ded_particular_group(){
	var dynamic_ded_particulars = $('.dynamic_ded_particulars').first().clone().removeClass('hidden');

	dynamic_ded_particulars.find('.deduction_name').attr('name', 'deduction_name[]');
	dynamic_ded_particulars.find('.deduction_particular_amount').attr('name', 'deduction_particular_rate[]');
	dynamic_ded_particulars.find('.pformat').priceFormat({prefix:''});

	$('.deduction_particulars_container').append(dynamic_ded_particulars);
}

function delete_particular_group(element){
	$(element).closest('.particular_group').remove();
	calculate_total_amount();
}

function change_particular_type(element){
	var rate_type = $('option:selected', element).attr('rate_type');
	var type_name = "";
	if(rate_type=='d')
		type_name = "Daily";
	else if(rate_type=='m')
		type_name = "Monthly";
	else
		type_name = "-";

	$(element).parent().parent().find('.particular_rate_type').html(type_name);
}

$(document).ready(function(){
	calculate_total_amount();

	$('.pformat').priceFormat({prefix:''});
	
	$('form').submit(function(e){
		e.preventDefault();
		var that = $(this),
			submitBtn = that.find('[type=submit]'),
			msgBox = $('.alert-danger');

		msgBox.addClass('hidden');
		
		$.post(that.data('action'), that.serialize())
		.done(function(response){
			var resp = jQuery.parseJSON(response);
			if(resp.result){
				window.location.href = $('.cancel').attr('href');
				return;
			}
			msgBox.removeClass('hidden').find('ul').html('<li>'+resp.messages.join('</li><li>')+'</li>');
			$('html, body').animate({scrollTop: 0}, 'slow');
		})
		.fail(function(){
			alert('An internal error has occured. Please try again.');
		});
	})
})