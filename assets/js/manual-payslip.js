function change_days(element){
	$('.days_rendered').each(function(){
		$(this).val($(element).val());
	});

	calculate_particular_amount();
}

function cha(element){
	var particular_rate = 0;
	var particular_days_rendered = 0;
	var particular_unit = 0

	particular_rate = $(element).val();
	particular_days_rendered = $('.days_rendered').val();

	console.log(particular_rate);
	console.log(particular_days_rendered);

	var particular_amount = (particular_rate * 10) * particular_days_rendered;
	$(element).parent().parent().find('.particular_amount').html(commaSeparateNumber(particular_amount.toFixed(2)));

	calculate_total_amount();
}

function calculate_particular_amount(element){

	var particular_rate = [];
	$('.particular_rate').each(function(index, value){
		particular_rate.push(Number(($(this).val()).replace(",", "")));
	});

	var days_rendered = $('.days_rendered').val();

	$('.particular_amount').each(function(index, value){
		var tot = Number(particular_rate[index]) * Number(days_rendered);
		$(this).html(commaSeparateNumber(tot.toFixed(2)))
	});

	calculate_total_amount();
}

function calculate_late_amount(){
	var late_minutes = Number(($('.late_minutes').val()).replace(",", ""));
	var late_rate = Number(($('.late_rate').val()).replace(",", ""));

	var tot = late_minutes * late_rate;
	$('.late_amount').html(commaSeparateNumber(tot.toFixed(2)));

	calculate_total_amount();
}

function calculate_overtime_amount(){
	var overtime_hours = $('.overtime_time').val();
	var overtime_rate = Number(($('.overtime_rate').html()).replace(",", ""));

	var tot = overtime_hours * overtime_rate;
	$('.overtime_amount').html(commaSeparateNumber(tot.toFixed(2)));

	calculate_total_amount();
}

function calculate_total_amount(){
	var total_additional_amount = 0;
	$('.particular_amount').each(function(){
		total_additional_amount += Number(($(this).html()).replace(",", ""));
	});
	total_additional_amount += Number(($('.overtime_amount').html()).replace(",", ""));

	var total_deduction_amount = 0;
	$('.deduction_particular_amount').each(function(){
		total_deduction_amount += Number(($(this).val()).replace(",", ""));
	});
	$('.loan_payment_amount').each(function(){
		total_deduction_amount += Number(($(this).html()).replace(",", ""));
	});
	total_deduction_amount += Number(($('.late_amount').html()).replace(",", ""));

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

function select_employee(element){
    if($(element).val()!=""){
        $.post($(element).data('url'), {id: $(element).val()}, function(response){
            $('#payslip_forms').html(response);
        });
    }
    else
        $('#payslip_forms').html('');
}

$(document).on('keyup', '.particular_rate', function(){
	var particular_rate = 0;
	var particular_days_rendered = 0;
	var particular_unit = 0

	particular_rate = $(this).val();
	particular_days_rendered = $('.days_rendered').val();

	console.log(particular_rate);
	console.log(particular_days_rendered);

	var p_type = $('.p_type').html();
	var particular_amount = 0;

	if(p_type=='Monthly')
		particular_amount = particular_rate;
	else if(p_type=='Daily')
		particular_amount = particular_rate * particular_days_rendered;
	$(this).parent().parent().find('.particular_amount').html(commaSeparateNumber(particular_amount.toFixed(2)));

	calculate_total_amount();
});

$(document).on('keyup', '.deduction_particular_amount', function(){
	calculate_total_amount();
});

$(document).ready(function(){
    $('.datepicker').datepicker();

	$('form').submit(function(e){
		e.preventDefault();

		var that = $(this),
			submitBtn = that.find('[type=submit]'),
			msgBox = $('.alert-danger');

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
			alert('An internal error has occured. Please try again.');
		});
	});
})