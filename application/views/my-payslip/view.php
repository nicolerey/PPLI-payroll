<style type="text/css">
  table td{
    vertical-align: middle!important;
  }
  .form-group{
    margin-bottom:5px!important;
  }
</style>
<?php $url = base_url('my_payslip')?>
<section class="content-header">
  <h1>
    My Payslips
    <small></small>
  </h1>
</section>
<section class="content">

  <!-- Default box -->

  <div class="box box-solid">
    <form class="form-horizontal" data-action="<?= base_url("payslip/adjust/{$payslip['id']}")?>">
      <input type="hidden" name="employee_id" value="<?= $payslip['employee_id']?>"/>
      <input type="hidden" name="id" value="<?= $payslip['id']?>"/>
      <div class="box-body">
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>

        <div class="form-group">
          <div class="col-sm-8"></div>
          <label class="col-sm-2 control-label"> Date</label>
          <div class="col-sm-2">
            <p class="form-control-static"><?= date('Y-m-d');?></p>
          </div>
        </div>

        <div class="form-group">
          <label class="col-sm-2 control-label"> Employee name</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= "{$employee_data['firstname']} {$employee_data['middleinitial']} {$employee_data['lastname']}"?></p>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"> Payroll period</label>
          <div class="col-sm-5">
            <p class="form-control-static"><?= format_date($payslip['start_date'], 'd-M-Y'). ' - '. format_date($payslip['end_date'], 'd-M-Y')?></p>
          </div>
        </div>
        <hr/>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-hover table-striped">
              <thead>
                <tr>
                  <th class="col-sm-1"></th>
                  <th class="col-sm-3">Particulars</th>
                  <th class="col-sm-2">Rate type</th>
                  <th class="col-sm-2">Rate</th>
                  <th class="col-sm-1">No. of days</th>
                  <th class="col-sm-1">No. of units</th>
                  <th class="col-sm-2">Amount</th>
                </tr>
              </thead>
              <tbody class="additional_particulars_container">
                <tr>
                  <td></td>
                  <td>Basic Rate</td>
                  <td>Daily</td>
                  <td class="basic_rate">
                    <input name="basic_rate"  min="0" step="0.01" class="form-control pformat particular_rate" onchange="calculate_particular_amount(this, 0);" value="<?= $payslip['current_daily_wage'];?>"/>
                  </td>
                  <td class="particular_days_rendered"><?= $payslip['days_rendered'];?></td>
                  <td>
                    <input type="number" class="form-control particular_unit" value="<?= $payslip['daily_wage_units'];?>" onchange="calculate_particular_amount(this, 0);" name="basic_rate_units[]"/>
                  </td>
                  <td class="particular_amount">
                    <?= number_format($payslip['current_daily_wage'] * $payslip['days_rendered'] * $payslip['daily_wage_units'], 2);?>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td>Overtime</td>
                  <td>Daily</td>
                  <td>-</td>
                  <td>-</td>
                  <td>-</td>
                  <td class="particular_amount"><?= number_format($payslip['overtime_pay'], 2);?></td>
                </tr>
                <?php if($payslip['particulars']['additionals']):?>
                  <?php foreach($payslip['particulars']['additionals'] as $additionals):?>
                    <?php
                      if($additionals['particular_type']=='d')
                        $add_type = "Daily";
                      else
                        $add_type = "Monthly";
                    ?>
                    <tr>
                      <td></td>
                      <td>
                        <?= $additionals['name'];?>
                        <input type="hidden" name="particular_id[]" value="<?= $additionals['id']?>"/>
                      </td>
                      <td><?= $add_type;?></td>
                      <td>
                        <input name="particular_rate[]"  min="0" step="0.01" class="form-control pformat particular_rate" onchange="calculate_particular_amount(this, 0);" value="<?= $additionals['amount'];?>"/>
                      </td>
                      <td class="particular_days_rendered"><?= $payslip['days_rendered'];?></td>
                      <td><input type="number" class="form-control particular_unit" name="units[]" value="<?= $additionals['units'];?>" onchange="calculate_particular_amount(this, 0);"/></td>
                      <td class="particular_amount">
                        <?= number_format($additionals['amount'] * $payslip['days_rendered'] * $additionals['units'], 2);?>
                      </td>
                    </tr>
                  <?php endforeach;?>
                <?php endif;?>
                <tr class="dynamic_add_particulars hidden particular_group">
                  <td>
                    <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                      <span class="glyphicon glyphicon-remove"></span>
                    </button>
                  </td>
                  <td>
                    <select class="form-control additional_name" onchange="change_particular_type(this);">
                      <option value=""></option>
                      <?php if(!empty($particulars)):?>
                        <?php foreach($particulars as $particular):?>
                          <?php if($particular['type']==='a'):?>
                            <option value="<?= $particular['id'];?>" rate_type="<?= $particular['particular_type'];?>"><?= $particular['name'];?></option>
                          <?php endif;?>
                        <?php endforeach;?>
                      <?php endif;?>
                    </select>
                  </td>
                  <td class="particular_rate_type">-</td>
                  <td>
                    <input name=""  min="0" step="0.01" value="0" class="form-control pformat particular_rate" onchange="calculate_particular_amount(this, 1);"/>
                  </td>
                  <td>
                    <input type="number" class="form-control particular_days_rendered" name="" value="0" onchange="calculate_particular_amount(this, 1);"/>
                  </td>
                  <td>
                    <input type="number" class="form-control particular_unit" name="" value="0" onchange="calculate_particular_amount(this, 1);"/>
                  </td>
                  <td class="particular_amount">
                    0.00
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-sm-12">
            <div class="col-sm-2">
              <button type="button" class="btn btn-flat btn-primary" onclick="add_particular_group(this);">
                <span class="glyphicon glyphicon-plus"></span> Add particular
              </button>
            </div>
            <div class="col-sm-7"></div>
            <div class="col-sm-2">
              <table class="table">
                <tbody>
                  <tr>
                    <td style="text-align: right;">
                      <label class="coltrol-label" style="margin-bottom: 0;"> Total:</label>
                    </td>
                    <td class="total_additional">0.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="row">
            <label class="col-sm-8 control-label text-danger"> Deductions:</label>
          </div>
          <div class="row">
            <div class="col-sm-7"></div>
            <div class="col-sm-4">
              <table class="table table-hover table-striped text-danger">
                <thead>
                  <tr>
                    <th class="col-sm-1"></th>
                    <th class="col-sm-6">Particulars</th>
                    <th class="col-sm-3">Amount</th>
                  </tr>
                </thead>
                <tbody class="deduction_particulars_container">
                  <?php if($payslip['particulars']['deductions']):?>
                    <?php foreach($payslip['particulars']['deductions'] as $key=>$deductions):?>
                      <?php if($key!=='loan'):?>
                        <?php
                          if($deductions['type']=='d')
                            $ded_type = "Daily";
                          else
                            $ded_type = "Monthly";
                        ?>
                        <tr>
                          <td></td>
                          <td><?= $deductions['name'];?></td>
                          <td>
                            <input  min="0" step="0.01" value="<?= $deductions['amount'];?>" class="form-control pformat deduction_particular_amount" onchange="calculate_total_amount();"<?= ($key!=='loan')?'name="particular_rate[]"':'';?>/>
                            <?php if($key!=='loan'):?>
                              <input type="hidden" name="particular_id[]" value="<?= $deductions['id']?>"/>
                            <?php endif;?>
                          </td>
                        </tr>
                      <?php endif;?>
                      <?php if($key==='loan'):?>
                        <?php foreach($deductions as $loan):?>
                          <tr>
                            <td></td>
                            <td>Loan Payment - <?= $loan['payment_date'];?></td>
                            <td class="loan_payment_amount"><?= $loan['payment_amount'];?></td>
                          </tr>
                        <?php endforeach;?>
                      <?php endif;?>
                    <?php endforeach;?>
                  <?php endif;?>
                  <tr class="dynamic_ded_particulars hidden particular_group">
                    <td>
                      <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                        <span class="glyphicon glyphicon-remove"></span>
                      </button>
                    </td>
                    <td>
                      <select class="form-control deduction_name">
                        <option value=""></option>
                        <?php if(!empty($particulars)):?>
                          <?php foreach($particulars as $particular):?>
                            <?php if($particular['type']==='d'):?>
                              <option value="<?= $particular['id'];?>"><?= $particular['name'];?></option>
                            <?php endif;?>
                          <?php endforeach;?>
                        <?php endif;?>
                      </select>
                    </td>
                    <td>
                      <input name=""  min="0" step="0.01" value="0" class="form-control pformat deduction_particular_amount" onchange="calculate_total_amount();"/>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-7"></div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-flat btn-primary" onclick="ded_particular_group();">
                <span class="glyphicon glyphicon-plus"></span> Add particular
              </button>
            </div>
            <div class="col-sm-2">
              <table class="table table-hover table-striped">
                <thead>
                </thead>
                <tbody>
                  <tr>
                    <td style="text-align: right;"><label class="coltrol-label" style="margin-bottom: 0;"> Net Pay:</label></td><td class="net_pay">2400.00</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- <div class=> -->
      </div><!-- /.box-body -->
      <div class="box-footer clearfix">
        <a href="<?=$url?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat">Save payslip</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>