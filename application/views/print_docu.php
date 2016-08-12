<?= plugin_css('bootstrap/css/bootstrap.min.css')?>

<style type="text/css">
	.form-group{
		margin-bottom: 0;
	}
</style>

<?php 
$chunk_by_four = array_chunk($payslips, 4);
foreach ($chunk_by_four as $chunk_by_four_key => $chunk_by_four_value):?>
	<div>
		<?php
		$chunk_by_two = array_chunk($chunk_by_four_value, 2);
		foreach($chunk_by_two as $chunk_by_two_key=>$chunk_by_two_value):?>
			<div style="height: 50%;">
				<?php
				foreach($chunk_by_two_value as $employee_key=>$employee_value):?>
					<div class="col-xs-6 form-horizontal" style="height: 100%; border: 1px solid;">
						<div class="form-group">
							<label class="col-xs-2">Date:</label>
							<label class="col-xs-8"><?= $employee_value['date'];?></label>
						</div>
						<div class="form-group">
							<label class="col-xs-3">Employee Name:</label>
							<label class="col-xs-8"><u><?= $employee_value['employee_name'];?></u></label>
						</div>
						<div class="form-group">
							<label class="col-xs-7">Regular Wage:</label>
							<label class="col-xs-4"><u><?= number_format($employee_value['regular_wage'], 2);?></u></label>
						</div>
						<?php if($employee_value['overtime_pay']!=0):?>
							<div class="form-group">
								<label class="col-xs-7 control-label">Overtime Pay:</label>
								<label class="col-xs-4"><u><?= number_format($employee_value['overtime_pay'], 2);?></u></label>
							</div>
						<?php endif;?>
						<?php
						foreach($employee_value['additionals'] as $additional):?>					
							<?php if($additional['amount']!=0):?>
								<div class="form-group">
									<label class="col-xs-7 control-label"><?= $additional['name'];?>:</label>
									<label class="col-xs-4">
										<u>
											<?php $tot = ($additional['particular_type']=='d')?$additional['amount']*$employee_value['days_rendered']:$additional['amount'];?>
											<?= number_format($tot, 2);?>
										</u>
									</label>
								</div>
							<?php endif;?>
						<?php endforeach;?>
						<div class="form-group">
							<label class="col-xs-7">Deductions:</label>
						</div>						
						<?php if($employee_value['late_pay']!=0):?>
							<div class="form-group">
								<label class="col-xs-7 control-label">Late:</label>
								<label class="col-xs-4"><u><?= number_format($employee_value['late_pay'], 2);?></u></label>
							</div>
						<?php endif;?>
						<?php
						foreach($employee_value['deductions'] as $deduction):?>					
							<?php if($deduction['amount']!=0):?>
								<div class="form-group">
									<label class="col-xs-7 control-label"><?= $deduction['name'];?>:</label>
									<label class="col-xs-4">
										<u>
											<?= number_format($deduction['amount'], 2);?>
										</u>
									</label>
								</div>
							<?php endif;?>
						<?php endforeach;?>
						<?php
						foreach($employee_value['loan_payments'] as $loan_payment):?>					
							<?php if($loan_payment['payment_amount']!=0):?>
								<div class="form-group">
									<label class="col-xs-7 control-label">Loan: <?= $loan_payment['loan_name'];?>:</label>
									<label class="col-xs-4">
										<u>
											<?= number_format($loan_payment['payment_amount'], 2);?>
										</u>
									</label>
								</div>
							<?php endif;?>
						<?php endforeach;?>
						<div class="form-group">
							<label class="col-xs-7 control-label">TOTAL AMOUNT:</label>
							<label class="col-xs-4"><u><?= number_format($employee_value['net_pay'], 2);?></u></label>
						</div>
					</div>
				<?php endforeach;?>
			</div>
		<?php endforeach;?>
	</div>
<?php endforeach;?>

<?= plugin_script('bootstrap/js/bootstrap.min.js')?>