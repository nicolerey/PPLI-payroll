<style type="text/css">
  table td{
    vertical-align: middle!important;
  }
  .form-group{
    margin-bottom:5px!important;
  }
</style>
<?php $url = base_url('payslip')?>
<section class="content-header">
  <h1>
    Payslip
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->
  <div class="box box-solid">
    <div class="box-header with-border">
      <h3 class="box-title"><?= $title ?></h3>
    </div>
      <form class="form-horizontal" action="<?= "{$url}/store"?>" method="POST" onsubmit="return confirm('Are you sure?')">
      <div class="box-body">
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Name</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= "{$employee_data['firstname']} {$employee_data['middlename']} {$employee_data['lastname']}"?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Position</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= "{$employee_data['position']} @ {$employee_data['department']}"?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"> Month</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= format_date($from, 'd-M-Y'). ' - '. format_date($to, 'd-M-Y')?></p>
          </div>
        </div>
        <input type="hidden" name="month" value="<?= $month?>"/>
        <input type="hidden" name="employee_number" value="<?= $employee_data['id']?>"/>
        <hr/>
        <?php if(isset($data)):?>
          <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered">
                  <thead></thead>
                  <tbody>
                    <tr class="active"><td colspan="3" class="text-center text-bold">Earnings</td></tr>
                    <tr><td colspan="2" class="text-center">Particulars</td><td>Amount</td></tr>
                    <tr><td>Regular Pay</td><td><strong><?= preset($data, 'total_regular_days', 0)?></strong> <small>Days X </small><strong><?= preset($data, 'daily_wage', 0)?></strong></td><td class="text-bold text-right"><?= number_format(preset($data, 'regular_pay', 0), 2)?></td></tr>
                    <tr><td>Overtime Pay</td><td><strong><?= preset($data, 'total_overtime_hrs', 0)?></strong> <small>Hours X </small><strong><?= number_format(preset($data, 'overtime_hrly', 0), 2)?></strong></td><td class="text-bold text-right"><?= number_format(preset($data, 'regular_overtime_pay', 0), 2)?></td></tr>
                    <tr><td colspan="2">Wage Adjustment</td><td><input type="text" name="adjustment" class="form-control"></td></tr>
                    
                    <tr class="active"><td colspan="3" class="text-center text-bold">Additional</td></tr>
                    <tr><td colspan="2" class="text-center">Particulars</td><td>Amount</td></tr>
                    <?php if(isset($data['additionals'])):?>
                      <?php foreach($data['additionals'] AS $row):?>
                        <tr><td colspan="2"><?= $row['name']?></td><td class="text-bold text-right"><?= number_format($row['amount'], 2)?></td></tr>
                      <?php endforeach;?>
                    <?php elseif(!isset($data['additionals']) || !$data['additionals']):?>
                      <tr><td colspan="3" class="text-center">-</td></tr>
                    <?php endif;?>
                  </tbody>
                </table>
            </div>
            <div class="col-md-6">
               <table class="table table-bordered" >
                <tbody>
                  <tr class="active"><td colspan="3" class="text-center text-bold">Deductions</td></tr>
                  <tr><td colspan="2" class="text-center">Particulars</td><td>Amount</td></tr>
                  <?php if(isset($data['deductions'])):?>
                    <?php foreach($data['deductions'] AS $row):?>
                      <tr><td  colspan="2"><?= $row['name']?></td><td class="text-bold text-right"><?= number_format($row['amount'], 2)?></td></tr>
                    <?php endforeach;?>
                  <?php elseif(!isset($data['deductions']) || !$data['deductions']):?>
                    <tr><td colspan="3" class="text-center">-</td></tr>
                  <?php endif;?>
                  <tr><td>Late</td><td><strong><?= preset($data, 'total_late_minutes', 0)?></strong> <small>Minutes X </small><strong><?= preset($data, 'late_penalty', 0)?></strong></td><td class="text-bold text-right"><?= number_format(preset($data, 'total_late_deduction', 0), 2)?></td></tr>
                  <tr class="success"><td colspan="2" class="text-center text-bold">NET PAY</td><td class="text-bold text-right" style="font-size:130%"><?= number_format(preset($data, 'net_pay', 0), 2)?></td></tr>
                </tbody>
               </table>
            </div>
          </div>
        <?php endif;?>
        <!-- <div class=> -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?= "{$url}"?>" class="btn btn-default cancel pull-right btn-flat">Go back</a>
        <?php if(preset($data, 'net_pay', 0) > 0):?>
        <button type="submit" class="btn btn-success btn-flat">Save payslip</button>
        <?php endif;?>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>