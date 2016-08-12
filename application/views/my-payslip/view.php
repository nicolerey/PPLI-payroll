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

  <?php 
    $user_type = $this->session->userdata('account_type');
  ?>

  <!-- Default box -->

  <div class="box box-solid">
    <form class="form-horizontal" data-action="<?= base_url("payslip/adjust/{$payslip['id']}")?>">
      <input type="hidden" name="employee_id" value="<?= $payslip['employee_id']?>"/>
      <input type="hidden" name="id" value="<?= $payslip['id']?>"/>
      <div class="box-body">
        <div class="alert alert-danger hidden"><ul class="list-unstyled"></ul></div>

        <?php if($payslip['is_printed']):?>
          <div class="alert alert-info">This payslip has already been printed.</div>
        <?php endif;?>

        <div class="form-group">
          <label class="col-sm-2 control-label"> Status</label>
          <div class="col-sm-1">
            <p class="form-control-static approval_status"><?= ($payslip['approval_status'])?'Approved':'Pending';?></p>
          </div>
          <div class="col-sm-3">
            <?php if($this->session->userdata('account_type')=='ad'):?>
              <?php if(!$payslip['approval_status']):?>
                <button type="button" class="btn btn-flat btn-success btn-sm" id="approve_button" data-action="<?= base_url('my_payslip/approve_payslip')?>" data-pk="<?= $payslip['id'];?>"><i class="fa fa-check"></i> Approve payslip</button>
              <?php else:?>
                <button type="button" class="btn btn-flat btn-danger btn-sm" id="unapprove_button" data-action="<?= base_url('my_payslip/unapprove_payslip')?>" data-pk="<?= $payslip['id'];?>"><i class="fa fa-close"></i> Unapprove payslip</button>
              <?php endif;?>
            <?php endif;?>
          </div>
          <div class="col-sm-2"></div>
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
                  <th></th>
                  <th></th>
                  <th class="col-sm-2">Amount</th>
                </tr>
              </thead>
              <tbody class="additional_particulars_container">
                <tr>
                  <td></td>
                  <td>Basic Rate</td>
                  <td class="p_type">Daily</td>
                  <td class="basic_rate">
                    <input name="basic_rate"  min="0" step="0.01" class="form-control pformat particular_rate" value="<?= $payslip['current_daily_wage'];?>"<?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>/>
                  </td>
                  <td class="particular_days_rendered"><?= $payslip['days_rendered'];?></td>
                  <td style="padding-right: 50px;">day/s</td>
                  <td class="particular_amount">
                    <?= number_format($payslip['current_daily_wage'] * $payslip['days_rendered'] * $payslip['daily_wage_units'], 2);?>
                  </td>
                </tr>
                <tr>
                  <td></td>
                  <td>Overtime</td>
                  <td>Daily</td>
                  <td><?= number_format($payslip['overtime_pay'], 2);?></td>
                  <td><?= number_format($payslip['overtime_hours_rendered'], 2);?></td>
                  <td>hour/s</td>
                  <td class="particular_amount"><?= number_format($payslip['overtime_pay'] * $payslip['overtime_hours_rendered'], 2);?></td>
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
                      <td class="p_type"><?= $add_type;?></td>
                      <td>
                        <input name="particular_rate[]"  min="0" step="0.01" class="form-control pformat particular_rate" value="<?= $additionals['amount'];?>"<?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>/>
                      </td>
                      <td class="particular_days_rendered"><?= $payslip['days_rendered'];?></td>
                      <td>
                        day/s
                      </td>
                      <td class="particular_amount">
                        <?php if($add_type=='Daily'):?>
                          <?= number_format($additionals['amount'] * $payslip['days_rendered'], 2);?>
                        <?php elseif($add_type=='Monthly'):?>
                          <?= number_format($additionals['amount'], 2);?>
                        <?php endif;?>
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
                  <td class="particular_rate_type p_type">-</td>
                  <td>
                    <input name=""  min="0" step="0.01" value="0" class="form-control pformat particular_rate"/>
                  </td>
                  <td>
                    <?= $payslip['days_rendered'];?>
                  </td>
                  <td>
                    day/s
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
              <button type="button" class="btn btn-flat btn-primary" onclick="add_particular_group(this);"<?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>>
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
            <label class="col-sm-7 control-label text-danger"> Deductions:</label>
          </div>
          <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-7">
              <table class="table table-hover table-striped text-danger">
                <thead>
                  <tr>
                    <th class="col-sm-1"></th>
                    <th class="col-sm-3">Particulars</th>
                    <th class="col-sm-2">Rate type</th>
                    <th></th>
                    <th></th>
                    <th class="col-sm-2">Amount</th>
                  </tr>
                </thead>
                <tbody class="deduction_particulars_container">
                  <tr>
                    <td></td>
                    <td>Late penalties</td>
                    <td>-</td>
                    <td><?= number_format($payslip['late_minutes'], 2);?></td>
                    <td style="padding-right: 50px;">min/s</td>
                    <td class="late_penalty">
                      <?= number_format($payslip['current_late_penalty'] * $payslip['late_minutes'], 2);?>
                    </td>
                  </tr>
                  <?php if($payslip['particulars']['deductions']):?>
                    <?php foreach($payslip['particulars']['deductions'] as $key=>$deductions):?>
                      <?php
                        if($deductions['type']=='d')
                          $ded_type = "Daily";
                        else
                          $ded_type = "Monthly";
                      ?>
                      <tr>
                        <td></td>
                        <td><?= $deductions['name'];?></td>
                        <td class="p_type"><?= $ded_type;?></td>
                        <td><?= $payslip['days_rendered'];?></td>
                        <td>day/s</td>
                        <td>
                          <input  min="0" step="0.01" value="<?= $deductions['amount'];?>" class="form-control pformat deduction_particular_amount"<?= ($key!=='loan')?'name="particular_rate[]"':'';?><?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>/>
                          <?php if($key!=='loan'):?>
                            <input type="hidden" name="particular_id[]" value="<?= $deductions['id']?>"/>
                          <?php endif;?>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  <?php endif;?>
                  <?php if($loans):?>
                    <?php foreach($loans as $loan):?>
                      <tr>
                        <td></td>
                        <td>Loan: <?= $loan['loan_name'];?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          <input type="hidden" name="loan_id[]" value="<?= $loan['loan_id'];?>">
                          <input type="hidden" name="loan_payment_id[]" value="<?= $loan['id'];?>">
                          <input  min="0" step="0.01" value="<?= $loan['payment_amount'];?>" class="form-control pformat deduction_particular_amount" name="loan_payment[]"<?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>/>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  <?php endif;?>
                  <tr class="dynamic_ded_particulars hidden particular_group">
                    <td>
                      <button type="button" class="btn btn-flat btn-danger" onclick="delete_particular_group(this);">
                        <span class="glyphicon glyphicon-remove"></span>
                      </button>
                    </td>
                    <td>
                      <select class="form-control deduction_name" onchange="change_particular_type(this);">
                        <option value=""></option>
                        <?php if(!empty($particulars)):?>
                          <?php foreach($particulars as $particular):?>
                            <?php if($particular['type']==='d'):?>
                              <option value="<?= $particular['id'];?>" rate_type="<?= $particular['particular_type'];?>"><?= $particular['name'];?></option>
                            <?php endif;?>
                          <?php endforeach;?>
                        <?php endif;?>
                      </select>
                    </td>
                    <td class="particular_rate_type p_type">-</td>
                    <td><?= $payslip['days_rendered'];?></td>
                    <td>day/s</td>
                    <td>
                      <input name=""  min="0" step="0.01" value="0" class="form-control pformat deduction_particular_amount"/>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-7"></div>
            <div class="col-sm-2">
              <button type="button" class="btn btn-flat btn-primary" onclick="ded_particular_group();"<?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>>
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
        <a href="<?= base_url('my_payslip/view_payslip/'.$batch_id)?>" class="btn btn-default cancel pull-right btn-flat">Cancel</a>
        <button type="submit" class="btn btn-success btn-flat"<?= ($payslip['approval_status'] && $user_type!='ad')?'disabled':'';?>>Save payslip</button>
      </div><!-- /.box-footer -->
    </form>
  </div><!-- /.box -->
</section>